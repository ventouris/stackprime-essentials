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
    	$style = '<style type="text/css">
        			#login h1 a, .login h1 a {
            			background-image: url(' . $admin_ui['custom_login_page_logo'] . ');
						height: 160px;
						width: 320px;
						background-size: 160px 160px;
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
		$admin_ui = get_option('stackprime_admin_ui_options');
		return $admin_ui["custom_login_page_title"];
	}

	public function disable_application_passwords() {
		add_filter( 'wp_is_application_passwords_available', '__return_false' );
	}

	public function remove_script_style_version_parameter( $src ) {
		return strpos( $src, 'ver=' ) ? remove_query_arg( 'ver', $src ) : $src;
	}

	public function optimize_comment_js_loading() {
		if( is_singular() && comments_open() && get_comments_number() > 0 && get_option( 'thread_comments' ) === '1' ){
			wp_enqueue_script( 'comment-reply' );
		} else {
			wp_dequeue_script( 'comment-reply' );
		}
	}

	public function reduce_heartbeat_interval( $settings ) {
		$settings['autostart'] = false;
		$settings['interval'] = 60;
		return $settings;
	}
	
	public function disable_heartbeat_unless_post_edit_screen() {
		global $pagenow;
		if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' )
			wp_deregister_script('heartbeat');
	}
	


	public function update_stock_market() {
		$shortcodes = get_option('stackprime_shortcodes_options');

		if ( !wp_next_scheduled( 'get_stock_market_daily_data', array( "company" => $shortcodes["get_stock_market_data_company"]) ) ) {
			wp_schedule_event( time(), 'daily', 'get_stock_market_daily_data', array( "company" => $shortcodes["get_stock_market_data_company"]) );
		}
	}

	public function remove_update_stock_market() {
		$shortcodes = get_option('stackprime_shortcodes_options');
		wp_clear_scheduled_hook("get_stock_market_daily_data",  array( "company" => $shortcodes["get_stock_market_data_company"]));

	}

	public function get_stock_market_data( $data ) {
		
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			)
		); 
		$html = file_get_contents('https://finance.yahoo.com/quote/' . $data['company'], false, stream_context_create($arrContextOptions));
		$dom = new DOMDocument;
		libxml_use_internal_errors(true);
		$dom->loadHTML($html);
 		$arr = $dom->getElementsByTagName("td"); 
		foreach($arr as $item) { 
			$td = $item->getAttribute("data-test");
			if ($td == 'TD_VOLUME-value') {
				$volume = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));
			} elseif ($td == "PREV_CLOSE-value") {
				$prevClose = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));
			} elseif ( $td == "MARKET_CAP-value" ) {
				$marketCap = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));
			} 
		}
		if ($volume && $prevClose && $marketCap) {
			$data = json_encode(
				array(
					"date"=>date("Y-m-d"), 
					"regularMarketPreviousClose"=>$prevClose,
					"marketCap"=>$marketCap,
					"regularMarketVolume"=>$volume
				)
			);
			update_option( 'stock_market_data', $data);	
		}

	}

	public function stock_market_table( ) {
		$data = json_decode(get_option("stock_market_data"));
		
	    $html = '<div id="stock_market">
	   				<div class="stock_col">
						<span class="stock_label">Last Trade Price</span>
						<span id="price_value">' . $data->regularMarketPreviousClose . '</span>
					</div>
					<div class="stock_col">
						<span class="stock_label">Market Capitalisation</span>
						<span id="marketCap_value">' . $data->marketCap . '</span>
					</div>
					<div class="stock_col">
					<span class="stock_label">Volume</span>
					<span id="volume_value">' . $data->regularMarketVolume . '</span>
				</div>
				<div class="stock_date">Last update: ' . $data->date . '</div>
			</div>';
	   return $html;
	}

	public function start_modify_html() {
		ob_start();
	 }
	 
	public function end_modify_html() {
		$html = ob_get_clean();
		$style = "";
		preg_match_all('#<style>(.*?)</style>#is', $html, $matches, PREG_SET_ORDER);
		foreach($matches as $match) {
			$style .= $match[1];
		}
		echo '<style>' . $style . '</style>';
		echo preg_replace('#<style>(.*?)</style>#is', '', $html);
	 }

	public function send_email_to_customer_on_cancelled_order_in_woocommerce() {
		load_plugin_textdomain( 'send-email-to-customer-on-cancelled-order-in-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	public function seccow_send_email( $order_id, $old_status, $new_status, $order ){
		if ( $new_status == 'cancelled' || $new_status == 'failed' ){
			$wc_emails = WC()->mailer()->get_emails(); 
			$email_cliente = $order->get_billing_email(); 
		}
	
		if ( $new_status == 'cancelled' ) {
			$wc_emails['WC_Email_Cancelled_Order']->recipient .= ',' . $email_cliente;
			$wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );
		} 
		elseif ( $new_status == 'failed' ) {
			$wc_emails['WC_Email_Failed_Order']->recipient .= ',' . $email_cliente;
			$wc_emails['WC_Email_Failed_Order']->trigger( $order_id );
		} 
	}


	public function add_tracking_number_metabox( $post ) {

		    add_meta_box(
		            'Meta Box',
		            'Tracking number',
		            array($this, 'show_tracking_number'), 
		            'shop_order', 
		            'side',
		            'high'
		        );
		}

	public function show_tracking_number( $post ) {
		$data = get_post_meta($post->ID, '_tracking_number_data');
		if (count($data) >= 1) {
			$selected_company = $data[0]['company'];
			$selected_tracking_number = $data[0]['tracking_number'];
		} else {
			$selected_company = "";
			$selected_tracking_number = "";
		};
		
		$companies = array(
						"elta" => "ΕΛΤΑ",
						"tnt" => "TNT",
						"geniki" => "Γενική Ταχυδρομική",
						"speedex" => "Speedex",
						"acs" => "ACS Courier"
					);
		$courier_select = '<select name="tracking_number_company">
						<option disalbed="disabled">Choose Courier</option>';
		
		foreach ($companies as $key => $val) {
			$courier_select .= '<option ' . ($key == $selected_company ? 'selected = "selected"' : "") . ' value="' . $key . '">' . $val . '</option>';
		};
		$courier_select .= '</select>';
		echo $courier_select;
		
		echo '<br><br>';
		echo '<input type="text" name="tracking_number" placeholder="Tracking Number" value="' . $selected_tracking_number . '" />';
	
	}

	public function tracking_number_save_postdata( $post_id ) {

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;
	  
		// Check the user's permissions. If want
		if ( 'shop_order' == $_POST['post_type'] ) {
	  
		  if ( ! current_user_can( 'manage_woocommerce', $post_id ) )
			  return $post_id;
	  
		
		
			/* OK, its safe for us to save the data now. */
		
			$data = array("company"=> sanitize_text_field( $_POST['tracking_number_company'] ),
							"tracking_number" => sanitize_text_field( $_POST['tracking_number'] )
						);
		
			// Update the meta field in the database.
			update_post_meta( $post_id, '_tracking_number_data', $data ); // choose field name
			$order = wc_get_order(  $post_id );
			$order->add_order_note( 'Προστέθηκε tracking number' );
		}
	  }

	public function add_tracking_info_to_order_completed_email( $order, $sent_to_admin, $plain_text, $email ) {

		if ( 'customer_completed_order' == $email->id || 'customer_invoice' == $email->id ) {
			$order_id = $order->get_id();
			$data = get_post_meta($order_id, '_tracking_number_data');
			if (count($data) >= 1) {
				$selected_company = $data[0]['company'];
				$selected_tracking_number = $data[0]['tracking_number'];
				$tracking_url = '<a href="https://t.17track.net/en#nums=' . $selected_tracking_number . '">' . $selected_tracking_number . '</a>';
			} else {
				$selected_company = "";
				$selected_tracking_number = "";
			};
	
			
			// Quit if either tracking field is empty.
			if ( empty( $selected_tracking_number ) ) {
				return;
			}
	
			if ( $plain_text ) {
				printf( __("\nO αριθμός παρακολούθησης είναι %s με %s.\n", 'stackprime'), $tracking_url, $selected_company );
			}
			else {
				printf( __('<p>O αριθμός παρακολούθησης είναι %s με %s.</p>', 'stackprime'), $tracking_url, $selected_company );
			}
		}
	}

	public function sanitize_options( $input ) {

		// Define the array for the updated options
		$output = array();

		// Loop through each of the options sanitizing the data
		foreach( $input as $key => $val ) {

			if ( isset ( $input[$key] ) && ($val == "0" || $val == "1" )) {
				$output[$key] = $input[$key];
			}
			else if( isset ( $input[$key] ) ) {
				$output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
			} // end if

		} // end foreach

		// Return the new collection
		return apply_filters( 'sanitize_options', $output, $input );

	} 

	public function validate_input( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );

			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'validate_input', $output, $input );

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

		$html = '<input type="text" id="'.$input_name.'" name="'.$category.'['. $input_name .']" value="' . $options[$input_name] . '" />';
		$html .= '<label for="' . $input_name . '">&nbsp;'  . $description . '</label>';

		echo $html;

	} 


}