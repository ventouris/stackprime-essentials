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
		$html = file_get_contents('https://finance.yahoo.com/quote/' . $data, false, stream_context_create($arrContextOptions));
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
						"elta_courier" => "ΕΛΤΑ Courier",
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

			$companies = array(
				"elta" => "ΕΛΤΑ",
				"elta_courier" => "ΕΛΤΑ Courier",
				"tnt" => "TNT",
				"geniki" => "Γενική Ταχυδρομική",
				"speedex" => "Speedex",
				"acs" => "ACS Courier"
			);

			$urls = array(
				"elta" => "https://www.elta.gr/el-gr/%CE%B5%CE%BD%CF%84%CE%BF%CF%80%CE%B9%CF%83%CE%BC%CF%8C%CF%82%CE%B1%CE%BD%CF%84%CE%B9%CE%BA%CE%B5%CE%B9%CE%BC%CE%AD%CE%BD%CE%BF%CF%85.aspx",
				"elta_courier" => "https://www.elta-courier.gr/search",
				"tnt" => "https://www.tnt.com/express/el_gr/site/shipping-tools/tracking.html",
				"geniki" => "https://www.taxydromiki.com/track",
				"speedex" => "http://www.speedex.gr/isapohi.asp",
				"acs" => "https://www.acscourier.net/el/myacs/anafores-apostolwn/anazitisi-apostolwn/"
			);

			if (count($data) >= 1) {
				$selected_company = '<a href="' . $urls[$data[0]['company']] . '">' . $companies[$data[0]['company']] . '</a>';
				$selected_tracking_number = $data[0]['tracking_number'];
				$tracking_url =  $selected_tracking_number;
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

	public function check_new_vs_update( $post_id ){
		$myPost        = get_post($post_id);
		$post_created  = new DateTime( $myPost->post_date_gmt );
		$post_modified = new DateTime( $myPost->post_modified_gmt );
		$diff          = $post_created->diff( $post_modified );
		$seconds_difference = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
	
		if( $seconds_difference <= 1 ){
			return true;
		}else{
			return false;
		}
	}

	public function make_greeklish($slug) {
		$expressions = array(
			'/[αΑ][ιίΙΊ]/u' => 'e',
			'/[οΟΕε][ιίΙΊ]/u' => 'i',
			'/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'af$1',
			'/[αΑ][υύΥΎ]/u' => 'av',
			'/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'ef$1',
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
			'/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'if$1',
			'/[ηΗ][υΥ]/u' => 'iu',
			'/[θΘ]/u' => 'th',
			'/[χΧ]/u' => 'ch',
			'/[ψΨ]/u' => 'ps',
			'/[αά]/u' => 'a',
			'/[βΒ]/u' => 'v',
			'/[γΓ]/u' => 'g',
			'/[δΔ]/u' => 'd',
			'/[εέΕΈ]/u' => 'e',
			'/[ζΖ]/u' => 'z',
			'/[ηήΗΉ]/u' => 'i',
			'/[ιίϊΙΊΪ]/u' => 'i',
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
			'/[υύϋΥΎΫ]/u' => 'i',
			'/[φΦ]/iu' => 'f',
			'/[ωώ]/iu' => 'o',
		  );
		  
		  $text = preg_replace( array_keys($expressions), array_values($expressions), $slug);
		  $text = preg_replace('/\s+\D{1}(?!\S)|(?<!\S)\D{1}\s+/', '', $text);
		  $text = preg_replace( array('/&.*?;/', '/\s+/', '/[^A-Za-z0-9_\.\-]/u'),  array(' ', '-', ''), $text );
		  $text = filter_var(strtolower($text), FILTER_SANITIZE_URL);
		  return $text;
	  }	

	public function slug_save_post_callback( $post_ID, $post, $update ) {
		// allow 'publish', 'draft', 'future'
		if ($post->post_status == 'auto-draft' or $post->post_type == 'acf-field')
			return;
	
		// only change slug when the post is created (both dates are equal)
		if (!$this->check_new_vs_update($post_ID))
			return;
	
		$new_slug = $this->make_greeklish($post->post_title);

		// unhook this function to prevent infinite looping
		remove_action( 'save_post', array($this, 'slug_save_post_callback'), 10, 3);
		// update the post slug (WP handles unique post slug)
		wp_update_post( array(
			'ID' => $post_ID,
			'post_name' => $new_slug
		));
		// re-hook this function
		add_action( 'save_post', array($this,'slug_save_post_callback'), 10, 3);
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
 
	// let's remove query args first
	$redirect = remove_query_arg( array( 'stack_perc_sale_price_10',
	'stack_perc_sale_price_15',  
		'stack_perc_sale_price_20',
		'stack_perc_sale_price_25',  
		'stack_perc_sale_price_30',
		'stack_perc_sale_price_40',
		'stack_perc_sale_price_50',
		'stack_stop_sale' ), $redirect );
	
	if ( $doaction == "stack_stop_sale" ) {
		foreach ( $object_ids as $post_id ) {
			$product = wc_get_product( $post_id ); // Handling variable products
			if ( $product ) {
				if ( $product->is_type( 'variable' ) ) {
					$variations = $product->get_available_variations();
					foreach ( $variations as $variation ) {
						$temp_variation = wc_get_product($variation['variation_id']);
						$regular_price = $temp_variation->get_regular_price();
						$temp_variation->set_price( $regular_price );
						$temp_variation->set_sale_price( '' );
						$temp_variation->set_date_on_sale_to( '' );
						$temp_variation->set_date_on_sale_from( '' );
						$temp_variation->save();
					}	
				} else {
					$regular_price = $product->get_regular_price();
					$product->set_price( $regular_price );
					$product->set_sale_price( '' );
					$product->set_date_on_sale_to( '' );
					$product->set_date_on_sale_from( '' );
					$product->save();
				}
				$product->save();
				WC_Cache_Helper::get_transient_version( 'product', true );
				delete_transient( 'wc_products_onsale' );
			}		
		}
		$redirect = add_query_arg('stack_sales_removed', $redirect );

	} elseif ( strpos($doaction, 'stack_perc_sale_price') !== false ) {
		
		if ( $doaction == 'stack_perc_sale_price_10') {
			$multiply_price_by = 0.9;
		} elseif ( $doaction == 'stack_perc_sale_price_15' ) {
			$multiply_price_by = 0.85;
		} elseif ( $doaction == 'stack_perc_sale_price_20' ) {
			$multiply_price_by = 0.8;
		} elseif ( $doaction == 'stack_perc_sale_price_25' ) {
			$multiply_price_by = 0.75;
		} elseif ( $doaction == 'stack_perc_sale_price_30' ) {
			$multiply_price_by = 0.7;
		} elseif ( $doaction == 'stack_perc_sale_price_40' ) {
			$multiply_price_by = 0.6;
		} elseif ( $doaction == 'stack_perc_sale_price_50' ) {
			$multiply_price_by = 0.5;
		} else {
			$multiply_price_by = 1.0;
		}

		foreach ( $object_ids as $post_id ) {
			$product = wc_get_product( $post_id ); // Handling variable products
			if ( $product ) {
				if ( $product->is_type( 'variable' ) ) {
					$variations = $product->get_available_variations();
					foreach ( $variations as $variation ) {
						$temp_variation = wc_get_product($variation['variation_id']);
						$regular_price = $temp_variation->get_regular_price();
						$temp_variation->set_price( $regular_price * $multiply_price_by );
						$temp_variation->set_sale_price( $regular_price * $multiply_price_by );
						$temp_variation->set_date_on_sale_from( '' );
						$temp_variation->save();
					}
				} else {
					$regular_price = $product->get_regular_price();
					$product->set_price( $regular_price * $multiply_price_by );
					$product->set_sale_price( $regular_price * $multiply_price_by );
					$product->set_date_on_sale_from( '' );
					$product->save();
				}
				$product->save();
				WC_Cache_Helper::get_transient_version( 'product', true );
				delete_transient( 'wc_products_onsale' );
			}		
		}
 
		// do not forget to add query args to URL because we will show notices later
		$redirect = add_query_arg(
			'stack_perc_sale_price_done', // just a parameter for URL (we will use $_GET['misha_make_draft_done'] )
			count( $object_ids ), // parameter value - how much posts have been affected
		$redirect );
 
	}
	return $redirect;
 
}

 
public function stack_bulk_action_notices() {

	// but you can create an awesome message
	if( ! empty( $_REQUEST['stack_perc_sale_price_done'] ) ) {
 
		// depending on ho much posts were changed, make the message different
		printf( '<div id="message" class="updated notice is-dismissible"><p>' .
			_n( 'Price of %s product has been changed.',
			'Price of %s products has been changed.',
			intval( $_REQUEST['stack_perc_sale_price_done'] )
		) . '</p></div>', intval( $_REQUEST['stack_perc_sale_price_done'] ) );
 
	} elseif ( ! empty( $_REQUEST['stack_sales_removed'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>All sales have been removed.</p>
		</div>';
	}
 
}

}