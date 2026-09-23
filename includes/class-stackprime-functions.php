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
class Stackprime_Functions {

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
	public function __construct(  ) {

	}


	public function admin_bar_remove_logo() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
	}

	public function remove_update_available_notice() {
		if( !current_user_can( 'update_core' ) ) {
			remove_action( 'admin_notices', 'update_nag', 3 );
		}
	}

	public function custom_login_logo() { 
		$admin_ui = get_option('stackprime_admin_ui_options');
		$admin_ui = array(
			'custom_login_page_logo'       => esc_url( ! empty( $admin_ui['custom_login_page_logo'] ) ? $admin_ui['custom_login_page_logo'] : plugins_url( 'assets/img/logo.png', dirname( __FILE__ ) ) ),
			'custom_login_page_background' => esc_url( ! empty( $admin_ui['custom_login_page_background'] ) ? $admin_ui['custom_login_page_background'] : plugins_url( 'assets/img/login_bg.jpeg', dirname( __FILE__ ) ) ),
			'custom_login_page_color'      => ! empty( $admin_ui['custom_login_page_color'] ) && sanitize_hex_color( $admin_ui['custom_login_page_color'] ) ? sanitize_hex_color( $admin_ui['custom_login_page_color'] ) : '#000000',
		);
    	$style = '<style type="text/css">
        			#login h1 a, .login h1 a {
            			background-image: url(' . $admin_ui['custom_login_page_logo'] . ');
						height: 120px;
						width: auto;
						background-size: contain;
						background-repeat: no-repeat;
						padding-bottom: 10px;
					}
					@media screen and (max-width: 820px) {
						body:after {
							display: none;
						}
						body {
							width: 100% !important;
						}
					}
					body:after {
						content: "";
						width: 50vw;
						position: absolute;
						height: 100%;
						background-image: url(' . $admin_ui['custom_login_page_background'] . ');
						right: 0;
						top: 0;
						background-repeat: no-repeat;
						background-size: cover;
						box-shadow: 5px 5px 5px 5px black;
					}
					body {
						width: 50%;
					}
					.wp-core-ui .button-primary {
						background: ' . $admin_ui["custom_login_page_color"] . ' !important;
						border-color: ' . $admin_ui["custom_login_page_color"] . ' !important;
					}
    			</style>';
		echo $style;
 	}

	 public function custom_login_logo_url() {
		return home_url();
	}

 
	public function custom_login_logo_url_title() {
		return get_bloginfo('name');
	}

	public function limit_post_revisions( $num ) {
		return ( $num < 0 || $num > 5 ) ? 5 : $num;
	}

	public function remove_script_style_version_parameter( $src ) {
		return strpos( $src, 'ver=' ) ? remove_query_arg( 'ver', $src ) : $src;
	}

	public function optimize_comment_js_loading() {
		if ( is_admin() ) {
			return;
		}
		if( is_singular() && comments_open() && get_comments_number() > 0 && get_option( 'thread_comments' ) === '1' ){
			wp_enqueue_script( 'comment-reply' );
		} else {
			wp_dequeue_script( 'comment-reply' );
		}
	}

	public function reduce_heartbeat_interval( $settings ) {
		$settings['interval'] = 60;
		return $settings;
	}
	
	public function disable_heartbeat_unless_post_edit_screen() {
		global $pagenow;
		// Heartbeat handles post/order locking and autosave on edit screens, including the HPOS order editor.
		$is_order_edit = 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'wc-orders' === $_GET['page'];
		if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' && ! $is_order_edit ) {
			wp_deregister_script('heartbeat');
			// The session-expired login modal depends on Heartbeat and cannot work without it.
			add_filter( 'wp_auth_check_load', '__return_false' );
		}
	}
	


	public function update_stock_market() {
		if ( ! wp_next_scheduled( 'get_stock_market_daily_data' ) ) {
			// Older versions scheduled the event with the company as an argument; clear those first.
			wp_unschedule_hook( 'get_stock_market_daily_data' );
			wp_schedule_event( time(), 'daily', 'get_stock_market_daily_data' );
		}
	}

	public function remove_update_stock_market() {
		wp_unschedule_hook( 'get_stock_market_daily_data' );
	}

	public function stock_market_options_updated( $old_value, $value ) {
		$enabled = is_array( $value ) && isset( $value['get_stock_market_data'] ) && "1" == $value['get_stock_market_data'];
		if ( ! $enabled ) {
			$this->remove_update_stock_market();
			delete_option( 'stock_market_data' );
			return;
		}

		$old_company = is_array( $old_value ) && isset( $old_value['get_stock_market_data_company'] ) ? $old_value['get_stock_market_data_company'] : '';
		$company = isset( $value['get_stock_market_data_company'] ) ? $value['get_stock_market_data_company'] : '';
		if ( $old_company !== $company ) {
			// Don't show the previous company's data until the next daily run; fetch the new one right away.
			delete_option( 'stock_market_data' );
			wp_schedule_single_event( time(), 'get_stock_market_daily_data' );
		}
	}

	public function get_stock_market_data() {
		$shortcodes = get_option('stackprime_shortcodes_options');
		$company = is_array( $shortcodes ) && ! empty( $shortcodes['get_stock_market_data_company'] ) ? $shortcodes['get_stock_market_data_company'] : '';
		if ( '' === $company ) {
			return;
		}

		$response = wp_remote_get( 'https://finance.yahoo.com/quote/' . rawurlencode( $company ), array( 'timeout' => 15 ) );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return;
		}
		$html = wp_remote_retrieve_body( $response );
		if ( '' === $html ) {
			return;
		}

		$dom = new DOMDocument;
		libxml_use_internal_errors(true);
		$dom->loadHTML($html);
		libxml_clear_errors();

		// Yahoo renders the quote statistics as <fin-streamer data-field="..." data-value="...">.
		$xpath = new DOMXPath( $dom );
		$fields = array();
		foreach ( array( 'regularMarketPreviousClose', 'marketCap', 'regularMarketVolume' ) as $field ) {
			$node = $xpath->query( '//*[@data-field="' . $field . '"]' )->item( 0 );
			$value = null;
			if ( $node ) {
				$value = $node->getAttribute( 'data-value' );
				if ( '' === $value ) {
					$value = $node->nodeValue;
				}
				$value = trim( preg_replace( "/\s+/", " ", $value ) );
			}
			$fields[ $field ] = $value;
		}
		$prevClose = $fields['regularMarketPreviousClose'];
		$marketCap = $fields['marketCap'];
		$volume = $fields['regularMarketVolume'];
		if ($volume && $prevClose && $marketCap) {
			$data = json_encode(
				array(
					"date"=>wp_date("Y-m-d"),
					"regularMarketPreviousClose"=>$prevClose,
					"marketCap"=>$marketCap,
					"regularMarketVolume"=>$volume
				)
			);
			update_option( 'stock_market_data', $data, false );
		}

	}

	public function stock_market_table( ) {
		$data = json_decode( (string) get_option( "stock_market_data", '' ) );
		if ( ! is_object( $data ) ) {
			return '';
		}
		
	    $html = '<table id="stock_market">
					<tr>
						<th class="stock_label">Previous Close</th>
						<th class="stock_label">Market Capitalisation</th>
						<th class="stock_label">Volume</th>
					</tr>
					<tr>
						<td id="price_value">' . esc_html( $data->regularMarketPreviousClose ) . '</td>
						<td id="marketCap_value">' . esc_html( $data->marketCap ) . '</td>
						<td id="volume_value">' . esc_html( $data->regularMarketVolume ) . '</td>
					</tr>		
				</table>
				<div class="stock_date">Last update: ' . esc_html( $data->date ) . '</div>';
	   return $html;
	}

	public function start_modify_html() {
		ob_start();
	 }
	 
	public function end_modify_html() {
		$html = ob_get_clean();
		// Move whole <style> tags (keeping attributes such as media or id) to where the buffer started, inside <head>.
		$body = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $html);
		// preg_replace returns null on PCRE errors (e.g. backtrack limit on huge pages); never output an empty page.
		if ( null === $body || ! preg_match_all('#<style\b[^>]*>.*?</style>#is', $html, $matches) ) {
			echo $html;
			return;
		}
		echo implode( "\n", $matches[0] );
		echo $body;
	 }

	public function seccow_send_email( $order_id, $old_status, $new_status, $order ){
		$email_classes = array(
			'cancelled' => 'WC_Email_Cancelled_Order',
			'failed'    => 'WC_Email_Failed_Order',
		);

		if ( ! isset( $email_classes[ $new_status ] ) ) {
			return;
		}

		// Newer WooCommerce versions ship customer emails for these statuses; they are enabled
		// through the woocommerce_email_enabled_* filters, so there is nothing to do here.
		// Check the files, since loading the mailer instantiates every email class.
		if ( defined( 'WC_ABSPATH' )
			&& file_exists( WC_ABSPATH . 'includes/emails/class-wc-email-customer-cancelled-order.php' )
			&& file_exists( WC_ABSPATH . 'includes/emails/class-wc-email-customer-failed-order.php' ) ) {
			return;
		}

		$wc_emails = WC()->mailer()->get_emails();

		$customer_email = $order->get_billing_email();
		$email = isset( $wc_emails[ $email_classes[ $new_status ] ] ) ? $wc_emails[ $email_classes[ $new_status ] ] : null;

		if ( empty( $customer_email ) || ! $email ) {
			return;
		}

		// The email objects are shared for the whole request, so send to the customer only
		// and restore the recipient. WooCommerce already notifies the admin on its own.
		$original_recipient = $email->recipient;
		$email->recipient = $customer_email;
		$email->trigger( $order_id );
		$email->recipient = $original_recipient;
	}


	/**
	 * Turn on WooCommerce's customer cancelled/failed order emails, which are off by default,
	 * unless the email's own Enable setting has been saved in WooCommerce > Settings > Emails.
	 */
	public function enable_customer_email_unless_configured( $enabled, $object = null, $email = null ) {
		if ( $email instanceof WC_Email ) {
			$settings = get_option( $email->get_option_key() );
			if ( is_array( $settings ) && isset( $settings['enabled'] ) ) {
				return $enabled;
			}
		}
		return true;
	}

	private function get_tracking_companies() {
		return array(
			"elta" => "ΕΛΤΑ",
			"elta_courier" => "ΕΛΤΑ Courier",
			"tnt" => "TNT",
			"geniki" => "Γενική Ταχυδρομική",
			"speedex" => "Speedex",
			"acs" => "ACS Courier"
		);
	}

	private function get_tracking_data( $order ) {
		$data = $order ? $order->get_meta( '_tracking_number_data', true ) : null;
		return array(
			'company'         => is_array( $data ) && isset( $data['company'] ) ? $data['company'] : '',
			'tracking_number' => is_array( $data ) && isset( $data['tracking_number'] ) ? $data['tracking_number'] : '',
		);
	}

	public function add_tracking_number_metabox() {
		// With HPOS enabled orders are edited on their own screen instead of the shop_order post screen.
		$screen = function_exists( 'wc_get_page_screen_id' ) ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';

		add_meta_box(
			'stackprime_tracking_number',
			'Tracking number',
			array($this, 'show_tracking_number'),
			$screen,
			'side',
			'high'
		);
	}

	public function show_tracking_number( $post_or_order ) {
		$order = $post_or_order instanceof WP_Post ? wc_get_order( $post_or_order->ID ) : $post_or_order;
		$data = $this->get_tracking_data( $order );

		echo '<select name="tracking_number_company">';
		echo '<option value="">Choose Courier</option>';
		foreach ( $this->get_tracking_companies() as $key => $val ) {
			echo '<option value="' . esc_attr( $key ) . '"' . selected( $key, $data['company'], false ) . '>' . esc_html( $val ) . '</option>';
		}
		echo '</select>';

		echo '<br><br>';
		echo '<input type="text" name="tracking_number" placeholder="Tracking Number" value="' . esc_attr( $data['tracking_number'] ) . '" />';
	}

	/**
	 * Hooked to woocommerce_process_shop_order_meta, which WooCommerce fires once per order
	 * save (legacy and HPOS) after checking the nonce and the user's permissions.
	 */
	public function tracking_number_save_postdata( $order_id, $post_or_order = null ) {
		if ( ! isset( $_POST['tracking_number_company'], $_POST['tracking_number'] ) ) {
			return;
		}

		$order = $post_or_order instanceof WC_Order ? $post_or_order : wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$company = sanitize_key( wp_unslash( $_POST['tracking_number_company'] ) );
		if ( ! array_key_exists( $company, $this->get_tracking_companies() ) ) {
			$company = '';
		}

		$data = array(
			"company" => $company,
			"tracking_number" => sanitize_text_field( wp_unslash( $_POST['tracking_number'] ) )
		);

		if ( $data === $this->get_tracking_data( $order ) ) {
			return;
		}

		// WC_Meta_Box_Order_Data::save (priority 40) reloads and saves the order right after this,
		// so set the meta on that save instead of saving the order (and firing its hooks) twice.
		$order_id = $order->get_id();
		$apply = function ( $object ) use ( $order_id, $data, &$apply ) {
			if ( ! $object instanceof WC_Order || $object->get_id() !== $order_id ) {
				return;
			}
			remove_action( 'woocommerce_before_order_object_save', $apply );

			if ( '' === $data['tracking_number'] ) {
				$object->delete_meta_data( '_tracking_number_data' );
			} else {
				$object->update_meta_data( '_tracking_number_data', $data );
			}
		};
		add_action( 'woocommerce_before_order_object_save', $apply );

		if ( '' !== $data['tracking_number'] ) {
			$order->add_order_note( 'Προστέθηκε tracking number' );
		}
	}

	public function add_tracking_info_to_order_completed_email( $order, $sent_to_admin, $plain_text, $email ) {

		if ( 'customer_completed_order' == $email->id || 'customer_invoice' == $email->id ) {
			$data = $this->get_tracking_data( $order );

			// Quit if the tracking number is empty.
			if ( empty( $data['tracking_number'] ) ) {
				return;
			}

			$companies = $this->get_tracking_companies();

			$urls = array(
				"elta" => "https://www.elta.gr/el-gr/%CE%B5%CE%BD%CF%84%CE%BF%CF%80%CE%B9%CF%83%CE%BC%CF%8C%CF%82%CE%B1%CE%BD%CF%84%CE%B9%CE%BA%CE%B5%CE%B9%CE%BC%CE%AD%CE%BD%CE%BF%CF%85.aspx",
				"elta_courier" => "https://www.elta-courier.gr/search",
				"tnt" => "https://www.tnt.com/express/el_gr/site/shipping-tools/tracking.html",
				"geniki" => "https://www.taxydromiki.com/track",
				"speedex" => "https://www.speedex.gr/isapohi.asp",
				"acs" => "https://www.acscourier.net/el/myacs/anafores-apostolwn/anazitisi-apostolwn/"
			);

			$company_key = $data['company'];
			$company_name = isset( $companies[ $company_key ] ) ? $companies[ $company_key ] : '';
			$company_url = isset( $urls[ $company_key ] ) ? $urls[ $company_key ] : '';

			if ( $plain_text ) {
				$selected_company = trim( $company_name . ' ' . $company_url );
				if ( '' === $selected_company ) {
					printf( __("\nΟ αριθμός παρακολούθησης είναι %s.\n", 'stackprime'), $data['tracking_number'] );
				} else {
					printf( __("\nΟ αριθμός παρακολούθησης είναι %s με %s.\n", 'stackprime'), $data['tracking_number'], $selected_company );
				}
			}
			else {
				$selected_company = $company_url ? '<a href="' . esc_url( $company_url ) . '">' . esc_html( $company_name ) . '</a>' : esc_html( $company_name );
				if ( '' === $selected_company ) {
					printf( __('<p>Ο αριθμός παρακολούθησης είναι %s.</p>', 'stackprime'), esc_html( $data['tracking_number'] ) );
				} else {
					printf( __('<p>Ο αριθμός παρακολούθησης είναι %s με %s.</p>', 'stackprime'), esc_html( $data['tracking_number'] ), $selected_company );
				}
			}
		}
	}

	/**
	 * Sanitize callback for all option groups: checkboxes are stored as "1", the few
	 * text fields are sanitized according to what they hold.
	 */
	public function sanitize_options( $input ) {
		$output = array();

		if ( ! is_array( $input ) ) {
			return $output;
		}

		foreach ( $input as $key => $val ) {
			$val = is_string( $val ) ? trim( $val ) : '';

			if ( in_array( $key, array( 'custom_login_page_logo', 'custom_login_page_background' ), true ) ) {
				$output[ $key ] = esc_url_raw( $val );
			} elseif ( 'custom_login_page_color' === $key ) {
				$output[ $key ] = (string) sanitize_hex_color( $val );
			} elseif ( 'get_stock_market_data_company' === $key ) {
				$output[ $key ] = strtoupper( preg_replace( '/[^A-Za-z0-9.\-\^=]/', '', $val ) );
			} else {
				$output[ $key ] = '1' === $val ? '1' : '';
			}
		}

		return $output;
	}

	public function create_checkbox_input ( $args ) {
		$category = $args[0];
		$input_name = $args[1];
		$description = $args[2];

		$options = get_option($category);

		$html = '<input type="checkbox" id="' . $input_name . '" name="'. $category .'[' . $input_name . ']" value="1" ' . checked( 1, isset( $options[$input_name] ) ? $options[$input_name] : 0, false ) . '/>';
		$html .= '<label for="' . $input_name . '">&nbsp;'  . $description . '</label>';

		echo $html;
	}


	public function create_text_input( $args ) {

		$category = $args[0];
		$input_name = $args[1];
		$description = $args[2];

		$options = get_option( $category );
		$value = is_array( $options ) && isset( $options[ $input_name ] ) ? $options[ $input_name ] : '';

		$html = '<input type="text" id="'.$input_name.'" name="'.$category.'['. $input_name .']" value="' . esc_attr( $value ) . '" />';
		$html .= '<label for="' . $input_name . '">&nbsp;'  . $description . '</label>';

		echo $html;

	} 

	public function make_greeklish($slug) {
		$expressions = array(
			'/[αΑ][ιίΙΊ]/u' => 'e',
			'/[οΟΕε][ιίΙΊ]/u' => 'i',
			'/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτΤφΦχΧψΨ]|\s|$)/u' => 'af$1',
			'/[αΑ][υύΥΎ]/u' => 'av',
			'/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτΤφΦχΧψΨ]|\s|$)/u' => 'ef$1',
			'/[εΕ][υύΥΎ]/u' => 'ev',
			'/[οΟ][υύΥΎ]/u' => 'ou',
			'/(^|\s)[μΜ][πΠ]/u' => '$1b',
			'/[μΜ][πΠ](\s|$)/u' => 'b$1',
			'/[μΜ][πΠ]/u' => 'mp',
			'/[νΝ][τΤ]/u' => 'nt',
			'/[τΤ][σΣ]/u' => 'ts',
			'/[τΤ][ζΖ]/u' => 'tz',
			'/[γΓ][γΓ]/u' => 'ng',
			'/[γΓ][κΚ]/u' => 'gk',
			'/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτΤφΦχΧψΨ]|\s|$)/u' => 'if$1',
			'/[ηΗ][υΥ]/u' => 'iu',
			'/[θΘ]/u' => 'th',
			'/[χΧ]/u' => 'ch',
			'/[ψΨ]/u' => 'ps',
			'/[αάΑΆ]/u' => 'a',
			'/[βΒ]/u' => 'v',
			'/[γΓ]/u' => 'g',
			'/[δΔ]/u' => 'd',
			'/[εέΕΈ]/u' => 'e',
			'/[ζΖ]/u' => 'z',
			'/[ηήΗΉ]/u' => 'i',
			'/[ιίϊΐΙΊΪ]/u' => 'i',
			'/[κΚ]/u' => 'k',
			'/[λΛ]/u' => 'l',
			'/[μΜ]/u' => 'm',
			'/[νΝ]/u' => 'n',
			'/[ξΞ]/u' => 'x',
			'/[οόΟΌ]/u' => 'o',
			'/[πΠ]/u' => 'p',
			'/[ρΡ]/u' => 'r',
			'/[σςΣ]/u' => 's',
			'/[τΤ]/u' => 't',
			'/[υύϋΰΥΎΫ]/u' => 'i',
			'/[φΦ]/u' => 'f',
			'/[ωώΩΏ]/u' => 'o',
		  );
		  
		  $text = preg_replace( array_keys($expressions), array_values($expressions), $slug);
		  $text = preg_replace('/\s+\D{1}(?!\S)|(?<!\S)\D{1}\s+/', '', $text);
		  $text = preg_replace( array('/&.*?;/', '/\s+/', '/[^A-Za-z0-9_\.\-]/u'),  array(' ', '-', ''), $text );
		  $text = filter_var(strtolower($text), FILTER_SANITIZE_URL);
		  return $text;
	  }	

	/**
	 * Generate a greeklish slug at the moment WordPress creates one from the title,
	 * so no second save is needed. Explicit slugs and existing posts are left alone.
	 */
	public function greeklish_post_slug( $data, $postarr ) {
		$skip_types = array( 'revision', 'nav_menu_item', 'acf-field', 'shop_order', 'shop_order_placehold' );

		if ( in_array( $data['post_type'], $skip_types, true )
			|| in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft', 'inherit', 'trash' ), true )
			|| ! empty( $postarr['post_name'] ) ) {
			return $data;
		}

		$title = wp_unslash( $data['post_title'] );
		if ( ! preg_match( '/\p{Greek}/u', $title ) ) {
			return $data;
		}

		$slug = sanitize_title( $this->make_greeklish( $title ) );
		if ( '' === $slug ) {
			return $data;
		}

		$post_id = isset( $postarr['ID'] ) ? (int) $postarr['ID'] : 0;
		$data['post_name'] = wp_unique_post_slug( $slug, $post_id, $data['post_status'], $data['post_type'], $data['post_parent'] );

		return $data;
	}


 
public function stack_my_bulk_actions( $bulk_array ) {
 
	$bulk_array['stack_perc_sale_price_10'] = 'Add 10% discount';
	$bulk_array['stack_perc_sale_price_15'] = 'Add 15% discount';
	$bulk_array['stack_perc_sale_price_20'] = 'Add 20% discount';
	$bulk_array['stack_perc_sale_price_25'] = 'Add 25% discount';
	$bulk_array['stack_perc_sale_price_30'] = 'Add 30% discount';
	$bulk_array['stack_perc_sale_price_40'] = 'Add 40% discount';
	$bulk_array['stack_perc_sale_price_50'] = 'Add 50% discount';
	$bulk_array['stack_stop_sale'] = 'Remove sale prices';
	return $bulk_array;
 
}
 
public function stack_my_bulk_action_handler( $redirect, $doaction, $object_ids ) {

	$discounts = array(
		'stack_perc_sale_price_10' => 0.9,
		'stack_perc_sale_price_15' => 0.85,
		'stack_perc_sale_price_20' => 0.8,
		'stack_perc_sale_price_25' => 0.75,
		'stack_perc_sale_price_30' => 0.7,
		'stack_perc_sale_price_40' => 0.6,
		'stack_perc_sale_price_50' => 0.5,
	);

	// let's remove query args first
	$redirect = remove_query_arg( array( 'stack_perc_sale_price_done', 'stack_sales_removed' ), $redirect );

	if ( 'stack_stop_sale' !== $doaction && ! isset( $discounts[ $doaction ] ) ) {
		return $redirect;
	}

	$multiply_price_by = isset( $discounts[ $doaction ] ) ? $discounts[ $doaction ] : null;
	$changed = 0;

	// Load the selected products and their meta in two queries instead of a few per product.
	_prime_post_caches( array_map( 'absint', $object_ids ) );

	foreach ( $object_ids as $post_id ) {
		// edit.php only checks that the user can edit products in general, not each product.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			continue;
		}

		$product = wc_get_product( $post_id );
		if ( ! $product ) {
			continue;
		}
		$changed++;

		// get_children() includes out of stock and hidden variations and is much cheaper
		// than get_available_variations(), which builds the full frontend data.
		if ( $product->is_type( 'variable' ) ) {
			$children = $product->get_children();
			_prime_post_caches( $children );
			$targets = array_filter( array_map( 'wc_get_product', $children ) );
		} else {
			$targets = array( $product );
		}

		foreach ( $targets as $target ) {
			$regular_price = $target->get_regular_price();

			if ( null === $multiply_price_by ) {
				$target->set_price( $regular_price );
				$target->set_sale_price( '' );
				$target->set_date_on_sale_to( '' );
				$target->set_date_on_sale_from( '' );
			} else {
				if ( '' === $regular_price ) {
					continue;
				}
				$sale_price = wc_format_decimal( (float) $regular_price * $multiply_price_by, wc_get_price_decimals() );
				$target->set_price( $sale_price );
				$target->set_sale_price( $sale_price );
				// Start the sale now and without an end date. A leftover end date in the past would
				// keep the product off sale and make the wc_scheduled_sales cron remove the discount.
				$target->set_date_on_sale_from( '' );
				$target->set_date_on_sale_to( '' );
			}
			// Saving a variation schedules a sync of its parent's prices.
			$target->save();
		}
	}

	WC_Cache_Helper::get_transient_version( 'product', true );
	delete_transient( 'wc_products_onsale' );

	if ( null === $multiply_price_by ) {
		return add_query_arg( 'stack_sales_removed', 1, $redirect );
	}

	// do not forget to add query args to URL because we will show notices later
	return add_query_arg( 'stack_perc_sale_price_done', $changed, $redirect );
 
}

 
public function stack_bulk_action_notices() {

	// but you can create an awesome message
	if( ! empty( $_REQUEST['stack_perc_sale_price_done'] ) ) {
 
		// depending on ho much posts were changed, make the message different
		printf( '<div id="message" class="updated notice is-dismissible"><p>' .
			_n( 'Price of %s product has been changed.',
			'Price of %s products has been changed.',
			intval( $_REQUEST['stack_perc_sale_price_done'] ),
			'stackprime'
		) . '</p></div>', intval( $_REQUEST['stack_perc_sale_price_done'] ) );
 
	} elseif ( ! empty( $_REQUEST['stack_sales_removed'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>All sales have been removed.</p>
		</div>';
	}
 
}

}