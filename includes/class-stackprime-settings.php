<?php


/**
 * The admin-settings-specific functionality of the plugin.
 *
 * @link       https://www.stackprime.com
 * @since      1.0.0
 *
 * @package    Stackprime
 * @subpackage Stackprime
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Stackprime_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->functions = new Stackprime_Functions();

	}



	public function setup_plugin_options_menu() {
		
        //Add the menu to the Plugins set of menu items
		add_menu_page(
			'Stackprime', 					// The title to be displayed in the browser window for this page.
			'Stackprime',					// The text to be displayed for this menu item
			'manage_options',					// Which type of users can see this menu item
			'stackprime_options',			// The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content'),				// The name of the function to call when rendering this menu's page
            'dashicons-sos'
		);

	}


	public function default_admin_ui_options() {

		$defaults = array(
			"remove_admin_footer" => "1",
			"remove_wp_logo_on_header" => "1",
			"remove_update_available_notice" => "1",
			"split_admin_in_sections" => "1",
			"custom_login_page" => "0",
			"custom_login_logo" => "",
			"custom_login_background" => "",
			"custom_login_color" => "",
			"custom_login_title" => "",
		);

		return $defaults;

	}

	public function default_security_options() {

		$defaults = array(
			"disable_auto_updates_core" => "1",
			"disable_auto_updates_plugins" => "1",
			"disable_auto_updates_themes" => "1",
			"disallow_file_edit" => "1",
			"disable_application_passwords" => "1",
			"remove_generator_tag" => "1",
			"remove_script_style_version_parameter" => "1",
			"disable_comment_hyperlinks" => "1",
		);

		return $defaults;

	}


	public function default_performance_options() {

		$defaults = array(
			"limit_post_revisions" => "1",
			"remove_wlw_manifest_link" => "1",
			"remove_rsd_link" => "1",
			"remove_shortlink" => "1",
			"remove_feed_links" => "1",
			"remove_feed_generator_tag" => "1",
			"remove_wporg_dns_prefetch" => "1",
			"disable_emojis" => "1",
			"optimize_comment_js_loading" => "1",
			"remove_recent_comments_style" => "1",
			"reduce_heartbeat_interval" => "1",
			"disable_heartbeat_in_other_pages" => "1"
		);

		return $defaults;

	}

	public function default_shortcodes_options() {

		$defaults = array(
			"get_stock_market_data" => "",
			"greeklish_permalinks_only" => "1"
		);

		return $defaults;

	}

	public function default_woocommerce_options() {

		$defaults = array(
			"send_cancelled_email_to_client" => "",
			"woo_tracking_number" => ""

		);

		return $defaults;

	}

	public function default_misc_options() {

		$defaults = array(
			"move_styling_to_header" => "",
		);

		return $defaults;

	}


	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'Stackprime Essentials', 'stackprime' ); ?></h2>
			<?php settings_errors(); ?>

			<p class="stackprime-page-description">Use the below settings to unbloat your site from unwanted output. Remove admin nags and notifications, unnecessary items and performance-draining code. Please note that some options should only be set if you understand their consequences. If in doubt, leave options unchecked.</p>

			<?php if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = $_GET[ 'tab' ];
			} else if( $active_tab == 'admin_ui_options' ) {
				$active_tab = 'admin_ui_options';
			} else if( $active_tab == 'security_options' ) {
				$active_tab = 'security_options';
			} else if( $active_tab == 'performance_options' ) {
				$active_tab = 'performance_options';
			} else if( $active_tab == 'shortcodes_options' ) {
				$active_tab = 'shortcodes_options';	
			} else if( $active_tab == 'woocommerce_options' ) {
				$active_tab = 'woocommerce_options';	
			} else if( $active_tab == 'misc_options' ) {
				$active_tab = 'misc_options';	
			} else {
				$active_tab = 'admin_ui_options';
			} // end if/else ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=stackprime_options&tab=admin_ui_options" class="nav-tab <?php echo $active_tab == 'admin_ui_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Admin UI', 'stackprime' ); ?></a>
				<a href="?page=stackprime_options&tab=security_options" class="nav-tab <?php echo $active_tab == 'security_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Security', 'stackprime' ); ?></a>
				<a href="?page=stackprime_options&tab=performance_options" class="nav-tab <?php echo $active_tab == 'performance_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Performance', 'stackprime' ); ?></a>
				<a href="?page=stackprime_options&tab=shortcodes_options" class="nav-tab <?php echo $active_tab == 'shortcodes_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Shortcodes', 'stackprime' ); ?></a>
				<a href="?page=stackprime_options&tab=woocommerce_options" class="nav-tab <?php echo $active_tab == 'woocommerce_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Woocommerce', 'stackprime' ); ?></a>
				<a href="?page=stackprime_options&tab=misc_options" class="nav-tab <?php echo $active_tab == 'misc_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Misc', 'stackprime' ); ?></a>

			</h2>

			<form method="post" action="options.php" id="stackprime_settings_form">
				<?php

				if( $active_tab == 'admin_ui_options' ) {

					settings_fields( 'stackprime_admin_ui_options' );
					do_settings_sections( 'stackprime_admin_ui_options' );

				} elseif( $active_tab == 'security_options' ) {

					settings_fields( 'stackprime_security_options' );
					do_settings_sections( 'stackprime_security_options' );

				} elseif( $active_tab == 'performance_options' ) {

					settings_fields( 'stackprime_performance_options' );
					do_settings_sections( 'stackprime_performance_options' );

				} elseif( $active_tab == 'shortcodes_options' ) {

					settings_fields( 'stackprime_shortcodes_options' );
					do_settings_sections( 'stackprime_shortcodes_options' );

				} elseif( $active_tab == 'woocommerce_options' ) {

					settings_fields( 'stackprime_woocommerce_options' );
					do_settings_sections( 'stackprime_woocommerce_options' );

				} elseif( $active_tab == 'misc_options' ) {

					settings_fields( 'stackprime_misc_options' );
					do_settings_sections( 'stackprime_misc_options' );

				} 

				submit_button();

				?>
			</form>

		</div><!-- /.wrap -->
	<?php
	}


	public function admin_ui_options_callback() {
		$options = get_option('stackprime_admin_ui_options');
		echo '<p>' . __( 'In the Admin UI section, you will find settings that tweak the WP Admin interface.', 'stackprime' ) . '</p>';
	} 


	public function security_options_callback() {
		$options = get_option('stackprime_security_options');
		echo '<p>' . __( 'This is an important section that will harden your security. Read carefully and enable those that makes sense.', 'stackprime' ) . '</p>';
	} 

	public function performance_options_callback() {
		$options = get_option('stackprime_performance_options');
		echo '<p>' . __( 'Enable or disable options to improve the stability and performance of the website.', 'stackprime' ) . '</p>';
	} 

	public function shortcodes_options_callback() {
		$options = get_option('stackprime_shortcodes_options');
		echo '<p>' . __( 'Custom shortcodes for various functionalities. Enable and use those you need', 'stackprime' ) . '</p>';
	}

	public function woocommerce_options_callback() {
		$options = get_option('stackprime_woocommerce_options');
		echo '<p>' . __( 'Improvements and options for Woocommerce', 'stackprime' ) . '</p>';
	}
	
	public function misc_options_callback() {
		$options = get_option('stackprime_misc_options');
		echo '<p>' . __( 'Options that do not fit with the rest of the categories', 'stackprime' ) . '</p>';
	}



	public function initialize_admin_ui_options() {

		if( false == get_option( 'stackprime_admin_ui_options' ) ) {
			$default_array = $this->default_admin_ui_options();
			add_option( 'stackprime_admin_ui_options', $default_array );
		}

		add_settings_section(
			'admin_ui_settings_section',			            // ID used to identify this section and with which to register options
			__( 'Admin UI', 'stackprime' ),		        // Title to be displayed on the administration page
			array( $this, 'admin_ui_options_callback'),	    // Callback used to render the description of the section
			'stackprime_admin_ui_options'		                // Page on which to add this section of options
		);

		add_settings_field(
			'remove_admin_footer',
			__( 'Admin footer', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'remove_admin_footer',
				__( 'Remove the admin footer text Thank you for creating with WordPress.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_wp_logo_on_header',
			__( 'Wordpress logo', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'remove_wp_logo_on_header',
				__( 'Remove the Wordpress logo from admin bar.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_update_available_notice',
			__( 'Update Notice', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'remove_update_available_notice',
				__( 'Hide the WordPress update notice for non-Administrator users. The update notice will still be shown to Administrator-level users.', 'stackprime' ),
			)
		);

		add_settings_field(
			'split_admin_in_sections',
			__( 'Admin sections', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'split_admin_in_sections',
				__( 'Split admin options in sections based on the type of them', 'stackprime' ),
			)
		);

		add_settings_field(
			'custom_login_page',
			__( 'Login page', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'custom_login_page',
				__( 'Enable custom login page with split screen, personalised logo and colours' ),
			)
		);

		add_settings_field(
			'custom_login_page_logo',
			__( 'Login page (logo)', 'stackprime' ),
			array( $this->functions, 'create_text_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'custom_login_page_logo',
				__( 'Add the relative path of the logo image in the custom login page' ),
			)
		);

		add_settings_field(
			'custom_login_page_background',
			__( 'Login page (background)', 'stackprime' ),
			array( $this->functions, 'create_text_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'custom_login_page_background',
				__( 'Add the relative path of the background image in the custom login page' ),
			)
		);

		add_settings_field(
			'custom_login_page_color',
			__( 'Login page (color hex)', 'stackprime' ),
			array( $this->functions, 'create_text_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'custom_login_page_color',
				__( 'Add the hex color code of the custom login page' ),
			)
		);

		add_settings_field(
			'custom_login_page_title',
			__( 'Login page (title)', 'stackprime' ),
			array( $this->functions, 'create_text_input'),
			'stackprime_admin_ui_options',
			'admin_ui_settings_section',
			array(
				'stackprime_admin_ui_options',
				'custom_login_page_title',
				__( 'Add the title of the custom login page' ),
			)
		);


		register_setting(
			'stackprime_admin_ui_options',
			'stackprime_admin_ui_options'
		);

	} 

	public function initialize_security_options() {

		if( false == get_option( 'stackprime_security_options' ) ) {
			$default_array = $this->default_security_options();
			add_option( 'stackprime_security_options', $default_array );
		}

		add_settings_section(
			'security_settings_section',			            // ID used to identify this section and with which to register options
			__( 'Security', 'stackprime' ),		        // Title to be displayed on the administration page
			array( $this, 'security_options_callback'),	    // Callback used to render the description of the section
			'stackprime_security_options'		                // Page on which to add this section of options
		);

		add_settings_field(
			'disable_auto_updates_core',
			__( 'Auto updates (core)', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'disable_auto_updates_core',
				__( 'Disable the Core auto-update system completely for WordPress, plugin and theme updates. Enabling this option will overwrite the individual Plugin and Theme settings below.', 'stackprime' ),
			)
		);

		add_settings_field(
			'disable_auto_updates_plugins',
			__( 'Auto updates (plugins)', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'disable_auto_updates_plugins',
				__( 'Disable auto-updates for plugins (including the UI). The Auto-Updates setting above might overwrite this setting.', 'stackprime' ),
			)
		);

		add_settings_field(
			'disable_auto_updates_themes',
			__( 'Auto updates (themes)', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'disable_auto_updates_themes',
				__( 'Disable auto-updates for themes (including the UI). The Auto-Updates setting above might overwrite this setting.', 'stackprime' ),
			)
		);

		add_settings_field(
			'disallow_file_edit',
			__( 'Code Editors', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'disallow_file_edit',
				__( 'Disable the built-in code editors that allow users to modify plugin and theme code via the admin area. Please note that this option provides more security when set via the wp-config.php file.', 'stackprime' ),
			)
		);

		add_settings_field(
			'disable_application_passwords',
			__( 'Application Passwords', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'disable_application_passwords',
				__( 'Disable Application Passwords completely.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_generator_tag',
			__( 'Generator Tag', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'remove_generator_tag',
				__( 'Remove the Generator meta tag. Hides your WordPress version from plain sight.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_script_style_version_parameter',
			__( 'Script/Style Versions', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'remove_script_style_version_parameter',
				__( 'Remove the version parameter from styles and scripts. Hides your WordPress version some more.', 'stackprime' ),
			)
		);

		add_settings_field(
			'disable_comment_hyperlinks',
			__( 'Comment Hyperlinks', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_security_options',
			'security_settings_section',
			array(
				'stackprime_security_options',
				'disable_comment_hyperlinks',
				__( 'Disable automatic clickable hyperlinking of URLs in new comments. Gives a slight security advantage. Proper spam protection should always be in place though.', 'stackprime' ),
			)
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'stackprime_security_options',
			'stackprime_security_options'
		);

	} 

	public function initialize_performance_options() {

		if( false == get_option( 'stackprime_performance_options' ) ) {
			$default_array = $this->default_performance_options();
			add_option( 'stackprime_performance_options', $default_array );
		}

		add_settings_section(
			'performance_options_section',			            // ID used to identify this section and with which to register options
			__( 'Performance', 'stackprime' ),		        // Title to be displayed on the administration page
			array( $this, 'performance_options_callback'),	    // Callback used to render the description of the section
			'stackprime_performance_options'		                // Page on which to add this section of options
		);

		add_settings_field(
			'limit_post_revisions',
			__( 'Post Revisions', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'limit_post_revisions',
				__( 'Limit the number of post revisions to keep (per post) to a database-friendly maximum of 5', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_wlw_manifest_link',
			__( 'WLW Manifest', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'remove_wlw_manifest_link',
				__( 'Remove the WLW Manifest link. The Windows Live Writer (super old, dead software) Manifest is absolutely not needed anymore.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_rsd_link',
			__( 'Remove the RSD link', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'remove_rsd_link',
				__( 'The Really Simple Discovery link is used by some external editors and apps.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_shortlink',
			__( 'Shortlink', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'remove_shortlink',
				__( 'Remove the post shortlink URL', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_feed_links',
			__( 'Feed Links', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'remove_feed_links',
				__( 'Remove RSS (Really Simple Syndication) feed links for posts and comments. This will not disable the feeds itself, but just remove their link from the site\'s <head> section.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_feed_generator_tag',
			__( 'Feed Generator', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'remove_feed_generator_tag',
				__( 'Remove the generator tag from RSS feeds.', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_wporg_dns_prefetch',
			__( 'DNS Prefetch', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'remove_wporg_dns_prefetch',
				__( 'Remove the DNS prefetch to s.w.org', 'stackprime' ),
			)
		);

		add_settings_field(
			'disable_emojis',
			__( 'Emojis', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'disable_emojis',
				__( 'Disable WordPress\'s own emoji scripts and styles. This gets rid of a script, an inline script, inline styles and a DNS prefetch.', 'stackprime' ),
			)
		);

		add_settings_field(
			'optimize_comment_js_loading',
			__( 'Comment Script', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'optimize_comment_js_loading',
				__( 'Optimize the comment script by only loading it when needed (when comments are activated and open)', 'stackprime' ),
			)
		);

		add_settings_field(
			'remove_recent_comments_style',
			__( 'Recent Comments Style', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'remove_recent_comments_style',
				__( 'Remove an inline style block from the site\'s <head> that is used by old themes', 'stackprime' ),
			)
		);

		add_settings_field(
			'reduce_heartbeat_interval',
			__( 'Reduce Heartbeat', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'reduce_heartbeat_interval',
				__( 'Reduce the Heartbeat interval to save on admin-ajax usage. This will reduce the Heartbeat interval from 15 seconds (default) to 60 seconds in order to reduce server load.', 'stackprime' ),
			)
		);

		add_settings_field(
			'disable_heartbeat_in_other_pages',
			__( 'Remove Heartbeat', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_performance_options',
			'performance_options_section',
			array(
				'stackprime_performance_options',
				'disable_heartbeat_in_other_pages',
				__( 'Remove Heartbeat if you are not in an edit post page. Restrict WordPress heartbeat to speed up wp-admin when many browser tabs are open or traffic is high', 'stackprime' ),
			)
		);


		// Finally, we register the fields with WordPress
		register_setting(
			'stackprime_performance_options',
			'stackprime_performance_options'
		);

	} 

	public function initialize_shortcodes_options() {

		if( false == get_option( 'stackprime_shortcodes_options' ) ) {
			$default_array = $this->default_shortcodes_options();
			add_option( 'stackprime_shortcodes_options', $default_array );
		}

		add_settings_section(
			'shortcodes_options_section',			            // ID used to identify this section and with which to register options
			__( 'Shortcodes', 'stackprime' ),		        // Title to be displayed on the administration page
			array( $this, 'shortcodes_options_callback'),	    // Callback used to render the description of the section
			'stackprime_shortcodes_options'		                // Page on which to add this section of options
		);

		add_settings_field(
			'get_stock_market_data',
			__( 'Stock market price', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_shortcodes_options',
			'shortcodes_options_section',
			array(
				'stackprime_shortcodes_options',
				'get_stock_market_data',
				__( 'Get data from the stock market for the company and create a shortcode.', 'stackprime' ),
			)
		);

		add_settings_field(
			'get_stock_market_data_company',
			__( 'Company symbol', 'stackprime' ),
			array( $this->functions, 'create_text_input'),
			'stackprime_shortcodes_options',
			'shortcodes_options_section',
			array(
				'stackprime_shortcodes_options',
				'get_stock_market_data_company',
				__( 'The company symbol it has in stock market to fetch data from the yahoo finance.' ),
			)
		);



		// Finally, we register the fields with WordPress
		register_setting(
			'stackprime_shortcodes_options',
			'stackprime_shortcodes_options'
		);

	} 

	public function initialize_woocommerce_options() {

		if( false == get_option( 'stackprime_woocommerce_options' ) ) {
			$default_array = $this->default_woocommerce_options();
			add_option( 'stackprime_woocommerce_options', $default_array );
		}

		add_settings_section(
			'woocommerce_options_section',			            // ID used to identify this section and with which to register options
			__( 'Woocommerce', 'stackprime' ),		        // Title to be displayed on the administration page
			array( $this, 'woocommerce_options_callback'),	    // Callback used to render the description of the section
			'stackprime_woocommerce_options'		                // Page on which to add this section of options
		);

		add_settings_field(
			'send_cancelled_email_to_client',
			__( 'Cancelled emails to client', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_woocommerce_options',
			'woocommerce_options_section',
			array(
				'stackprime_woocommerce_options',
				'send_cancelled_email_to_client',
				__( 'By default, Woocommerce sends an email only on admins for cancelled orders. With this, the same email will be sent to the client', 'stackprime' ),
			)
		);

		add_settings_field(
			'woo_tracking_number',
			__( 'Tracking number', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_woocommerce_options',
			'woocommerce_options_section',
			array(
				'stackprime_woocommerce_options',
				'woo_tracking_number',
				__( 'Add a tracking number option on admin Woocommerce orders interface and include that info to the client email.', 'stackprime' ),
			)
		);


		// Finally, we register the fields with WordPress
		register_setting(
			'stackprime_woocommerce_options',
			'stackprime_woocommerce_options'
		);

	} 

	public function initialize_misc_options() {

		if( false == get_option( 'stackprime_misc_options' ) ) {
			$default_array = $this->default_misc_options();
			add_option( 'stackprime_misc_options', $default_array );
		}

		add_settings_section(
			'misc_options_section',			            // ID used to identify this section and with which to register options
			__( 'Misc', 'stackprime' ),		        // Title to be displayed on the administration page
			array( $this, 'misc_options_callback'),	    // Callback used to render the description of the section
			'stackprime_misc_options'		                // Page on which to add this section of options
		);

		add_settings_field(
			'move_styling_to_header',
			__( 'Inline style tags', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_misc_options',
			'misc_options_section',
			array(
				'stackprime_misc_options',
				'move_styling_to_header',
				__( 'Get all &lt;style&gt; elements and move them to the head tag. Choose this if you have an ESPA website to pass W3C validator', 'stackprime' ),
			)
		);

		add_settings_field(
			'greeklish_permalinks_only',
			__( 'Enable greeklish permalinks', 'stackprime' ),
			array( $this->functions, 'create_checkbox_input'),
			'stackprime_misc_options',
			'misc_options_section',
			array(
				'stackprime_misc_options',
				'greeklish_permalinks_only',
				__( 'Change permalinks from greek to greelish during post creation.', 'stackprime' ),
			)
		);


		// Finally, we register the fields with WordPress
		register_setting(
			'stackprime_misc_options',
			'stackprime_misc_options'
		);

	} 

	// public function textarea_element_callback() {

	// 	$options = get_option( 'wppb_demo_input_examples' );

	// 	// Render the output
	// 	echo '<textarea id="textarea_example" name="wppb_demo_input_examples[textarea_example]" rows="5" cols="50">' . $options['textarea_example'] . '</textarea>';

	// } 

	// public function checkbox_element_callback() {

	// 	$options = get_option( 'wppb_demo_input_examples' );

	// 	$html = '<input type="checkbox" id="checkbox_example" name="wppb_demo_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '/>';
	// 	$html .= '&nbsp;';
	// 	$html .= '<label for="checkbox_example">This is an example of a checkbox</label>';

	// 	echo $html;

	// } // end checkbox_element_callback

	// public function radio_element_callback() {

	// 	$options = get_option( 'wppb_demo_input_examples' );

	// 	$html = '<input type="radio" id="radio_example_one" name="wppb_demo_input_examples[radio_example]" value="1"' . checked( 1, $options['radio_example'], false ) . '/>';
	// 	$html .= '&nbsp;';
	// 	$html .= '<label for="radio_example_one">Option One</label>';
	// 	$html .= '&nbsp;';
	// 	$html .= '<input type="radio" id="radio_example_two" name="wppb_demo_input_examples[radio_example]" value="2"' . checked( 2, $options['radio_example'], false ) . '/>';
	// 	$html .= '&nbsp;';
	// 	$html .= '<label for="radio_example_two">Option Two</label>';

	// 	echo $html;

	// } // end radio_element_callback

	// public function select_element_callback() {

	// 	$options = get_option( 'wppb_demo_input_examples' );

	// 	$html = '<select id="time_options" name="wppb_demo_input_examples[time_options]">';
	// 	$html .= '<option value="default">' . __( 'Select a time option...', 'stackprime' ) . '</option>';
	// 	$html .= '<option value="never"' . selected( $options['time_options'], 'never', false) . '>' . __( 'Never', 'stackprime' ) . '</option>';
	// 	$html .= '<option value="sometimes"' . selected( $options['time_options'], 'sometimes', false) . '>' . __( 'Sometimes', 'stackprime' ) . '</option>';
	// 	$html .= '<option value="always"' . selected( $options['time_options'], 'always', false) . '>' . __( 'Always', 'stackprime' ) . '</option>';	$html .= '</select>';

	// 	echo $html;

	// } 




	public function run_enabled_options() {
		$admin_ui = get_option('stackprime_admin_ui_options');

		if ((isset($admin_ui['remove_admin_footer']) ? $admin_ui['remove_admin_footer'] : null) == "1") {
			add_filter( 'admin_footer_text', '__return_false' );
		}

		if ((isset($admin_ui['remove_wp_logo_on_header']) ? $admin_ui['remove_wp_logo_on_header'] : null) == "1") {
			add_action( 'wp_before_admin_bar_render', array($this->functions, 'admin_bar_remove_logo'), 0 );
		}

		if ((isset($admin_ui['remove_update_available_notice']) ? $admin_ui['remove_update_available_notice'] : null) == "1") {
			add_action( 'admin_head', array($this->functions, 'remove_update_available_notice'), 1 );
		}

		if ((isset($admin_ui['split_admin_in_sections']) ? $admin_ui['split_admin_in_sections'] : null) == "1") {
			include( plugin_dir_path( __FILE__ ) . 'helpers/clean-admin-menu.php');
		}

		if ((isset($admin_ui['custom_login_page']) ? $admin_ui['custom_login_page'] : null) == "1") {
			add_action( 'login_enqueue_scripts', array($this->functions, 'custom_login_logo'), 0);
			add_filter( 'login_headerurl', array($this->functions, 'custom_login_logo_url' ), 0);
			add_filter( 'login_headertext', array($this->functions, 'custom_login_logo_url_title' ), 0);
		}

		$security = get_option('stackprime_security_options');
		if ((isset($security['disable_auto_updates_core']) ? $security['disable_auto_updates_core'] : null) == "1") {
			defined( 'AUTOMATIC_UPDATER_DISABLED' ) || define( 'AUTOMATIC_UPDATER_DISABLED', true );
		}

		if ((isset($security['disable_auto_updates_plugins']) ? $security['disable_auto_updates_plugins'] : null) == "1") {
			add_filter( 'auto_update_plugin', '__return_false' );
			add_filter( 'plugins_auto_update_enabled', '__return_false' );
		}

		if ((isset($security['disable_auto_updates_themes']) ? $security['disable_auto_updates_themes'] : null) == "1") {
			add_filter( 'auto_update_theme', '__return_false' );
			add_filter( 'themes_auto_update_enabled', '__return_false' );
		}

		if ((isset($security['disallow_file_edit']) ? $security['disallow_file_edit'] : null) == "1") {
			defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', true );
		}

		if ((isset($security['disable_application_passwords']) ? $security['disable_application_passwords'] : null) == "1") {
			add_action( 'init', array($this->functions, 'disable_application_passwords') );
		}

		if ((isset($security['remove_generator_tag']) ? $security['remove_generator_tag'] : null) == "1") {
			remove_action( 'wp_head', 'wp_generator' );
		}

		if ((isset($security['remove_script_style_version_parameter']) ? $security['remove_script_style_version_parameter'] : null) == "1") {
			add_filter( 'style_loader_src',  array($this->functions, 'remove_script_style_version_parameter') , 9999 );
			add_filter( 'script_loader_src', array($this->functions, 'remove_script_style_version_parameter') , 9999 );
		}

		if ((isset($security['disable_comment_hyperlinks']) ? $security['disable_comment_hyperlinks'] : null) == "1") {
			add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}
		
		$performance = get_option('stackprime_performance_options');
		if ((isset($performance['limit_post_revisions']) ? $performance['limit_post_revisions'] : null) == "1") {
			defined( 'WP_POST_REVISIONS' ) || define( 'WP_POST_REVISIONS', 5 );
		}

		if ((isset($performance['remove_wlw_manifest_link']) ? $performance['remove_wlw_manifest_link'] : null) == "1") {
			remove_action( 'wp_head', 'wlwmanifest_link' );
		}

		if ((isset($performance['remove_rsd_link']) ? $performance['remove_rsd_link'] : null) == "1") {
			remove_action( 'wp_head', 'rsd_link' );
		}

		if ((isset($performance['remove_shortlink']) ? $performance['remove_shortlink'] : null) == "1") {
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		}

		if ((isset($performance['remove_feed_links']) ? $performance['remove_feed_links'] : null) == "1") {
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}

		if ((isset($performance['remove_wporg_dns_prefetch']) ? $performance['remove_wporg_dns_prefetch'] : null) == "1") {
			remove_action( 'wp_head', 'wp_resource_hints', 2 );
		}

		if ((isset($performance['remove_feed_generator_tag']) ? $performance['remove_feed_generator_tag'] : null) == "1") {
			remove_action( 'app_head', 'the_generator' );
			remove_action( 'atom_head', 'the_generator' );
			remove_action( 'comments_atom_head', 'the_generator' );
			remove_action( 'commentsrss2_head', 'the_generator' );
			remove_action( 'rdf_header', 'the_generator' );
			remove_action( 'rss_head', 'the_generator' );
			remove_action( 'rss2_head', 'the_generator' );
			remove_action( 'opml_head', 'the_generator' );		
		}

		if ((isset($performance['disable_emojis']) ? $performance['disable_emojis'] : null) == "1") {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_filter( 'embed_head', 'print_emoji_detection_script' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		}

		if ((isset($performance['optimize_comment_js_loading']) ? $performance['optimize_comment_js_loading'] : null) == "1") {
			add_action( 'wp_print_scripts', array($this->functions, 'optimize_comment_js_loading'), 100 );
		}

		if ((isset($performance['remove_recent_comments_style']) ? $performance['remove_recent_comments_style'] : null) == "1") {
			add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}

		if ((isset($performance['reduce_heartbeat_interval']) ? $performance['reduce_heartbeat_interval'] : null) == "1") {
			add_filter( 'heartbeat_settings', array($this->functions, 'reduce_heartbeat_interval') );
		}

		if ((isset($performance['disable_heartbeat_in_other_pages']) ? $performance['disable_heartbeat_in_other_pages'] : null) == "1") {
			add_action( 'init', array($this->functions, 'disable_heartbeat_unless_post_edit_screen'), 1 );
		}

		$shortcodes = get_option('stackprime_shortcodes_options');
		if ((isset($shortcodes['get_stock_market_data']) ? $shortcodes['get_stock_market_data'] : null) == "1") {
			add_action( 'wp', array($this->functions, 'update_stock_market') );
			add_action( 'get_stock_market_daily_data', array($this->functions, 'get_stock_market_data'), 1, 1 );
			add_shortcode( 'stockmarkettable', array($this->functions, 'stock_market_table') );
		}

		if ((isset($performance['get_stock_market_data']) ? $shortcodes['get_stock_market_data'] : "0") == "0") {
			wp_unschedule_event( time(), 'daily', 'get_stock_market_daily_data', array( "company" => $shortcodes["get_stock_market_data_company"]) );
			remove_action('daily', array($this->functions, 'remove_update_stock_market'));

		}

		$woocommerce = get_option('stackprime_woocommerce_options');
		if ((isset($woocommerce['send_cancelled_email_to_client']) ? $woocommerce['send_cancelled_email_to_client'] : null) == "1") {
			add_action( 'plugins_loaded', array($this->functions, 'send_email_to_customer_on_cancelled_order_in_woocommerce') );
			add_action('woocommerce_order_status_changed', array($this->functions, 'seccow_send_email'), 10, 4 );
		}

		if ((isset($woocommerce['woo_tracking_number']) ? $woocommerce['woo_tracking_number'] : null) == "1") {
			add_action( 'add_meta_boxes', array($this->functions, 'add_tracking_number_metabox'));
			add_action( 'save_post', array($this->functions, 'tracking_number_save_postdata') );
			add_action( 'woocommerce_email_order_details', array($this->functions, 'add_tracking_info_to_order_completed_email'), 5, 4 ); 
		}
		

		$misc = get_option('stackprime_misc_options');
		if ((isset($misc['move_styling_to_header']) ? $misc['move_styling_to_header'] : null) == "1") {
			add_action( 'wp_head', array($this->functions, 'start_modify_html') );
			add_action( 'wp_footer', array($this->functions, 'end_modify_html') );
		}
		if ((isset($misc['greeklish_permalinks_only']) ? $misc['greeklish_permalinks_only'] : null) == "1") {
			add_action( 'save_post', array($this->functions, 'slug_save_post_callback'), 10, 3);
		}
		

	}


}