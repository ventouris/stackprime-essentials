<?php

class Stackprime_Updater {

	const CACHE_KEY = 'stackprime_github_release';

	private $file;

	private $plugin;

	private $basename;

	private $username;

	private $repository;

	private $authorize_token;

	private $github_response;

	public function __construct( $file ) {

		$this->file = $file;
		// Needed before admin_init, since WordPress checks for plugin updates on admin_init too.
		$this->basename = plugin_basename( $file );

		return $this;
	}

	private function get_plugin_data() {
		if ( is_null( $this->plugin ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$this->plugin = get_plugin_data( $this->file, false, false );
		}
		return $this->plugin;
	}

	public function set_username( $username ) {
		$this->username = $username;
	}

	public function set_repository( $repository ) {
		$this->repository = $repository;
	}

	public function authorize( $token ) {
		$this->authorize_token = $token;
	}

	/**
	 * Fetch the latest release from GitHub, cached in a transient so the API is not
	 * hit on every update check. Returns null when no valid release is available.
	 */
	private function get_repository_info() {
		if ( ! is_null( $this->github_response ) ) {
			return $this->github_response ? $this->github_response : null;
		}

		$cached = get_site_transient( self::CACHE_KEY );
		if ( false !== $cached ) {
			$this->github_response = $cached;
			return $cached ? $cached : null;
		}

		$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases/latest', $this->username, $this->repository );
		$args = array( 'timeout' => 10 );

		if ( $this->authorize_token ) {
			$args['headers']['Authorization'] = "token {$this->authorize_token}";
		}

		$response = wp_remote_get( $request_uri, $args );
		$release = null;

		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( is_array( $body ) && ! empty( $body['tag_name'] ) && ! empty( $body['zipball_url'] ) ) {
				$release = $body;
			}
		}

		// Cache failures for a shorter time, so a GitHub outage or rate limit is retried later
		// without hammering the API. An empty string marks a cached failure.
		set_site_transient( self::CACHE_KEY, $release ? $release : '', $release ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
		$this->github_response = $release ? $release : '';

		return $release;
	}

	private function get_release_version( $release ) {
		return ltrim( $release['tag_name'], 'vV' );
	}

	public function initialize() {
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_transient' ), 10, 1 );
		add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3);
		add_filter( 'upgrader_source_selection', array( $this, 'rename_source' ), 10, 4 );
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 2 );

		// Add Authorization Token to download_package
		add_filter( 'upgrader_pre_download',
			function( $reply ) {
				add_filter( 'http_request_args', [ $this, 'download_package' ], 15, 2 );
				return $reply;
			}
		);
	}

	public function modify_transient( $transient ) {

		if ( ! is_object( $transient ) || empty( $transient->checked[ $this->basename ] ) ) {
			return $transient;
		}

		$release = $this->get_repository_info();
		if ( ! $release ) {
			return $transient;
		}

		$new_version = $this->get_release_version( $release );

		if ( version_compare( $new_version, $transient->checked[ $this->basename ], 'gt' ) ) {
			$plugin_data = $this->get_plugin_data();

			$transient->response[ $this->basename ] = (object) array(
				'url'         => $plugin_data['PluginURI'],
				'slug'        => dirname( $this->basename ),
				'plugin'      => $this->basename,
				'package'     => $release['zipball_url'],
				'new_version' => $new_version,
			);
		}

		return $transient;
	}

	public function plugin_popup( $result, $action, $args ) {

		if ( 'plugin_information' !== $action || empty( $args->slug ) || $args->slug !== dirname( $this->basename ) ) {
			return $result;
		}

		$release = $this->get_repository_info();
		if ( ! $release ) {
			return $result;
		}

		$plugin_data = $this->get_plugin_data();

		return (object) array(
			'name'              => $plugin_data['Name'],
			'slug'              => dirname( $this->basename ),
			'version'           => $this->get_release_version( $release ),
			'author'            => $plugin_data['AuthorName'],
			'author_profile'    => $plugin_data['AuthorURI'],
			'last_updated'      => isset( $release['published_at'] ) ? $release['published_at'] : '',
			'homepage'          => $plugin_data['PluginURI'],
			'short_description' => $plugin_data['Description'],
			'sections'          => array(
				'Description' => $plugin_data['Description'],
				'Updates'     => isset( $release['body'] ) ? $release['body'] : '',
			),
			'download_link'     => $release['zipball_url'],
		);
	}

	public function download_package( $args, $url ) {

		if ( null !== $args['filename'] ) {
			if( $this->authorize_token && false !== strpos( $url, $this->username . '/' . $this->repository ) ) {
				$args = array_merge( $args, array( "headers" => array( "Authorization" => "token {$this->authorize_token}" ) ) );
			}
		}

		remove_filter( 'http_request_args', [ $this, 'download_package' ] );

		return $args;
	}

	/**
	 * GitHub zipballs extract to "user-repo-sha", so rename the extracted folder to the
	 * plugin's own folder before it is installed. WordPress then installs, and for manual
	 * updates reactivates, the plugin at its usual path, in the admin and in cron alike.
	 */
	public function rename_source( $source, $remote_source, $upgrader, $hook_extra = array() ) {
		global $wp_filesystem;

		if ( is_wp_error( $source ) || empty( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== $this->basename ) {
			return $source;
		}

		$new_source = trailingslashit( $remote_source ) . dirname( $this->basename ) . '/';
		if ( untrailingslashit( $source ) === untrailingslashit( $new_source ) ) {
			return $source;
		}

		if ( ! $wp_filesystem->move( $source, $new_source, true ) ) {
			return new WP_Error( 'stackprime_rename_failed', __( 'Could not rename the downloaded plugin folder.', 'stackprime' ) );
		}

		return $new_source;
	}

	public function after_install( $response, $hook_extra ) {
		// This filter runs for every plugin/theme install and update, so only touch our own package.
		if ( ! empty( $hook_extra['plugin'] ) && $hook_extra['plugin'] === $this->basename ) {
			delete_site_transient( self::CACHE_KEY );
		}

		return $response;
	}
}
