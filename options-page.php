<?php

    $admin_ui = array( 			
		"remove_admin_footer" => array(
			"name" => "Admin footer",
			"description" => "Remove the admin footer text",
			"notes" => "Default: 'Thank you for creating with WordPress.'",
			"enable" => false
		),
		
		"remove_wp_logo_on_header" => array(
			"name" => "Wordpress logo",			
			"description" => "Remove the Wordpress logo from admin bar",
			"notes" => "",
			"enable" => true
		),
		
		"remove_update_available_notice" => array(
			"name" => "Update Notice",
			"description" => "Hide the WordPress update notice for non-Administrator users",
			"notes" => "The update notice will still be shown to Administrator-level users.",
			"enable" => true
		),
		
		"split_admin_in_sections" => array(
			"name" => "Admin sections",
			"description" => "Split admin options in sections based on the type of them",
			"notes" => "",
			"enable" => true
		),
		
		"custom_login_page" => array(
			"name" => "Login page",
			"description" => "Enable custom login page with split screen, personalised logo and colours.",
			"notes" => "",
			"data" => array("logo" => "/wp-content/uploads/2021/02/logo.png", 
							"background" => "/wp-content/uploads/2021/03/background.jpg", 
							"color" => "#000000",
						    "title" => "ΕΛΒΕ ΑΕ | Ποιότητα και Εξειδίκευση"),
			"enable" => true
		)
		
		
	);

	$security = array(
		"disable_auto_updates_core" => array(
			"name" => "Auto updates (core)",
			"description" => "Disable the Core auto-update system completely for WordPress, plugin and theme updates",
			"notes" => "Enabling this option will overwrite the individual Plugin and Theme settings below.",
			"enable" => false
		),
		
		"disable_auto_updates_plugins" => array(
			"name" => "Auto updates (plugins)",
			"description" => "Disable auto-updates for plugins (including the UI)",
			"notes" => "The 'Auto-Updates' setting above might overwrite this setting.",
			"enable" => false
		),
		
		"disable_auto_updates_themes" => array(
			"name" => "Auto updates (themes)",
			"description" => "Disable auto-updates for themes (including the UI)",
			"notes" => "The 'Auto-Updates' setting above might overwrite this setting.",
			"enable" => false
		),
		
		"disallow_file_edit" => array(
			"name" => "Code Editors",
			"description" => "Disable the built-in code editors that allow users to modify plugin and theme code via the admin area",
			"notes" => "Please note that this option provides more security when set via the wp-config.php file.",
			"enable" => false
		),
		
		"disable_application_passwords" => array(
			"name" => "Application Passwords",
			"description" => "Disable Application Passwords completely",
			"notes" => "",
			"enable" => true
		),
		
		"remove_generator_tag" => array(
			"name" => "Generator Tag",
			"description" => "Remove the Generator meta tag",
			"notes" => "Hides your WordPress version from plain sight.",
			"enable" => true
		),
		
		"remove_script_style_version_parameter" => array(
			"name" => "Script/Style Versions",
			"description" => "Remove the version parameter from styles and scripts",
			"notes" => "Hides your WordPress version some more.",
			"enable" => true
		),
		
		"disable_comment_hyperlinks" => array(
			"name" => "Comment Hyperlinks",
			"description" => "Disable automatic clickable hyperlinking of URLs in new comments",
			"notes" => "Gives a slight security advantage. Proper spam protection should always be in place though.",
			"enable" => true
		)
	);


	$performance = array(
		"limit_post_revisions" => array(
			"name" => "Post Revisions",
			"description" => "Limit the number of post revisions to keep (per post) to a database-friendly maximum of 5",
			"notes" => "",
			"enable" => true
		),
		
		"remove_wlw_manifest_link" => array(
			"name" => "WLW Manifest",
			"description" => "Remove the WLW Manifest link",
			"notes" => "The Windows Live Writer (super old, dead software) Manifest is absolutely not needed anymore.",
			"enable" => true
		),
		
		"remove_rsd_link" => array(
			"name" => "RSD Link",
			"description" => "Remove the RSD link",
			"notes" => "The Really Simple Discovery link is used by some external editors and apps.",
			"enable" => true
		),
		
		"remove_shortlink" => array(
			"name" => "Shortlink",
			"description" => "Remove the post shortlink URL",
			"notes" => "",
			"enable" => true
		),
		
		"remove_feed_links" => array(
			"name" => "Feed Links",
			"description" => "Remove RSS (Really Simple Syndication) feed links for posts and comments",
			"notes" => "This will not disable the feeds itself, but just remove their link from the site's <head> section.",
			"enable" => true
		),
		
		"remove_feed_generator_tag" => array(
			"name" => "Feed Generator",
			"description" => "Remove the generator tag from RSS feeds",
			"notes" => "",
			"enable" => true
		),
		
		"remove_wporg_dns_prefetch" => array(
			"name" => "DNS Prefetch",
			"description" => "Remove the DNS prefetch to s.w.org",
			"notes" => "",
			"enable" => true
		),
		
		"disable_emojis" => array(
			"name" => "Emojis",
			"description" => "Disable WordPress's own emoji scripts and styles",
			"notes" => "This gets rid of a script, an inline script, inline styles and a DNS prefetch.",
			"enable" => true
		),
		
		"optimize_comment_js_loading" => array(
			"name" => "Comment Script",
			"description" => "Optimize the comment script by only loading it when needed (when comments are activated and open)",
			"notes" => "",
			"enable" => true
		),
		
		"remove_recent_comments_style" => array(
			"name" => "Recent Comments Style",
			"description" => "Remove an inline style block from the site's <head> that is used by old themes",
			"notes" => "",
			"enable" => true
		),
		
		"reduce_heartbeat_interval" => array(
			"name" => "Reduce Heartbeat",
			"description" => "Reduce the Heartbeat interval to save on admin-ajax usage",
			"notes" => "This will reduce the Heartbeat interval from 15 seconds (default) to 60 seconds in order to reduce server load.",
			"enable" => true
		),
		
		"disable_heartbeat_in_other_pages" => array(
			"name" => "Remove Heartbeat",
			"description" => "Remove Heartbeat if you are not in an edit post page",
			"notes" => "Restrict WordPress heartbeat to speed up wp-admin when many browser tabs are open or traffic is high",
			"enable" => true
		)
		
	);

	$shortcodes = array( 		
		"get_stock_market_data" => array(
			"name" => "Stock market price",
			"description" => "Get data from the stock market for the company and create a shortcode",
			"notes" => "",
			"data" => array("company" => "ELBE.AT"),
			"enable" => true
		)
	);




// // // // // // // // // // // // //
// // // // // // // // // // // // // 
// ACTIVATE SETTINGS
// // // // // // // // // // // // //
// // // // // // // // // // // // // 


	if ( $admin_ui["remove_admin_footer"]["enable"] ) {
		add_filter( 'admin_footer_text', '__return_false' );
	}
	
	if ( $admin_ui["remove_wp_logo_on_header"]["enable"] ) {
		add_action( 'wp_before_admin_bar_render', 'admin_bar_remove_logo', 0 );
	}

	if ( $admin_ui["remove_update_available_notice"]["enable"] ) {
		add_action( 'admin_head', 'remove_update_available_notice' , 1 );
	}

	if ( $admin_ui["split_admin_in_sections"]["enable"] ) {
		include( plugin_dir_path( __FILE__ ) . 'inc/clean-admin-menu.php');
	}

	if ( $admin_ui["custom_login_page"]["enable"] ) {
		add_action( 'login_enqueue_scripts', 'custom_login_logo');
		add_filter( 'login_headerurl', 'custom_login_logo_url' );
		add_filter( 'login_headertext', 'custom_login_logo_url_title' );
	}

	if ( $security["disable_auto_updates_core"]["enable"] ) {
		defined( 'AUTOMATIC_UPDATER_DISABLED' ) || define( 'AUTOMATIC_UPDATER_DISABLED', true );
	}

	if ( $security["disable_auto_updates_plugins"]["enable"] ) {
		add_filter( 'auto_update_plugin', '__return_false' );
		add_filter( 'plugins_auto_update_enabled', '__return_false' );
	}

	if ( $security["disable_auto_updates_themes"]["enable"] ) {
		add_filter( 'auto_update_theme', '__return_false' );
		add_filter( 'themes_auto_update_enabled', '__return_false' );
	}

	if ( $security["disallow_file_edit"]["enable"] ) {
		defined( 'DISALLOW_FILE_EDIT' ) || define( 'DISALLOW_FILE_EDIT', true );
	}

	if ( $security["disable_application_passwords"]["enable"] ) {
		add_action( 'init', 'disable_application_passwords' );
	}

	if ( $security["remove_generator_tag"]["enable"] ) {
		remove_action( 'wp_head', 'wp_generator' );
	}

	if ( $security["remove_script_style_version_parameter"]["enable"] ) {
		add_filter( 'style_loader_src',  'remove_script_style_version_parameter' , 9999 );
		add_filter( 'script_loader_src', 'remove_script_style_version_parameter' , 9999 );
	}

	if ( $security["disable_comment_hyperlinks"]["enable"] ) {
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}


	if ( $performance["limit_post_revisions"]["enable"] ) {
		defined( 'WP_POST_REVISIONS' ) || define( 'WP_POST_REVISIONS', 5 );
	}

	if ( $performance["remove_wlw_manifest_link"]["enable"] ) {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}

	if ( $performance["remove_rsd_link"]["enable"] ) {
		remove_action( 'wp_head', 'rsd_link' );
	}

	if ( $performance["remove_shortlink"]["enable"] ) {
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	}

	if ( $performance["remove_feed_links"]["enable"] ) {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}

	if ( $performance["remove_wporg_dns_prefetch"]["enable"] ) {
		remove_action( 'wp_head', 'wp_resource_hints', 2 );
	}

	if ( $performance["remove_feed_generator_tag"]["enable"] ) {
		remove_action( 'app_head', 'the_generator' );
		remove_action( 'atom_head', 'the_generator' );
		remove_action( 'comments_atom_head', 'the_generator' );
		remove_action( 'commentsrss2_head', 'the_generator' );
		remove_action( 'rdf_header', 'the_generator' );
		remove_action( 'rss_head', 'the_generator' );
		remove_action( 'rss2_head', 'the_generator' );
		remove_action( 'opml_head', 'the_generator' );
	}

	if ( $performance["disable_emojis"]["enable"] ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_filter( 'embed_head', 'print_emoji_detection_script' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	if ( $performance["optimize_comment_js_loading"]["enable"] ) {
		add_action( 'wp_print_scripts', 'optimize_comment_js_loading', 100 );
	}

	if ( $performance["remove_recent_comments_style"]["enable"] ) {
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}

	if ( $performance["reduce_heartbeat_interval"]["enable"] ) {
		add_filter( 'heartbeat_settings', 'reduce_heartbeat_interval' );
	}

	if ( $performance["disable_heartbeat_in_other_pages"]["enable"] ) {
		add_action( 'init', 'disable_heartbeat_unless_post_edit_screen', 1 );
	}

	if ( $shortcodes["get_stock_market_data"]["enable"] ) {
		add_action( 'wp', 'update_stock_market' );
		add_action( 'get_stock_market_daily_data', 'get_stock_market_data', 1, 1 );
		add_shortcode( 'stockmarkettable', 'stock_market_table' );

	}

	

// // // // // // // // // // // // //
// // // // // // // // // // // // // 
// HELPER FUNCTIONS
// // // // // // // // // // // // //
// // // // // // // // // // // // // 

	function admin_bar_remove_logo() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
	}

	function remove_update_available_notice() {
		if( !current_user_can( 'update_core' ) ) {
			remove_action( 'admin_notices', 'update_nag', 3 );
		}
	}

	function disable_application_passwords() {
		add_filter( 'wp_is_application_passwords_available', '__return_false' );
	}

	function remove_script_style_version_parameter( $src ) {
		return strpos( $src, 'ver=' ) ? remove_query_arg( 'ver', $src ) : $src;
	}

	function optimize_comment_js_loading() {
		if( is_singular() && comments_open() && get_comments_number() > 0 && get_option( 'thread_comments' ) === '1' ){
			wp_enqueue_script( 'comment-reply' );
		} else {
			wp_dequeue_script( 'comment-reply' );
		}
	}
	
	function reduce_heartbeat_interval( $settings ) {
		$settings['autostart'] = false;
		$settings['interval'] = 60;
		return $settings;
	}
	
	function disable_heartbeat_unless_post_edit_screen() {
		global $pagenow;
		if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' )
			wp_deregister_script('heartbeat');
	}
	
	function update_stock_market() {
		global $shortcodes;
		if ( !wp_next_scheduled( 'get_stock_market_daily_data', array( "company" => $shortcodes["get_stock_market_data"]["data"]["company"]) ) ) {
			wp_schedule_event( time(), 'daily', 'get_stock_market_daily_data', array( "company" => $shortcodes["get_stock_market_data"]["data"]["company"]) );
		}
	}

	function get_stock_market_data( $data ) {
		
		$html = file_get_contents('https://finance.yahoo.com/quote/' . $data['company']);
		$dom = new DOMDocument;
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


	function custom_login_logo() { 
		global $admin_ui;
    	$style = '<style type="text/css">
        			#login h1 a, .login h1 a {
            			background-image: url(' . $admin_ui["custom_login_page"]['data']['logo'] . ');
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
						background-image: url(' . $admin_ui["custom_login_page"]['data']['background'] . ');
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
						background: ' . $admin_ui["custom_login_page"]['data']['color'] . ' !important;
						border-color: ' . $admin_ui["custom_login_page"]['data']['color'] . ' !important;
					}
    			</style>';
		echo $style;
 	}

	function custom_login_logo_url() {
		return home_url();
	}

 
	function custom_login_logo_url_title() {
		global $admin_ui;
		return $admin_ui["custom_login_page"]['data']['title'];
	}



// // // // // // // // // // // // //
// // // // // // // // // // // // // 
// SHORTCODES
// // // // // // // // // // // // //
// // // // // // // // // // // // // 

	function stock_market_table( ) {
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




// // // // // // // // // // // // //
// // // // // // // // // // // // // 
// SHOW SETTINGS IN ADMIN
// // // // // // // // // // // // //
// // // // // // // // // // // // // 
 
    function register_my_custom_submenu_page() {
        add_menu_page('Stackprime', 'Stackprime', 'manage_options', 'stackprime_menu', 'stackprime_custom_settings', 'dashicons-sos', 6);
    }

    add_action('admin_menu', 'register_my_custom_submenu_page',99);


	function print_setting($name, $data) {
		echo '<table class="form-table" role="presentation">';
		echo "<tr>";
		echo '<th scope="row"><label class="stackprime-page-option-title" for="' . $name . '">' . $data['name'] . '</label></th>';
		echo '<td>';
		echo '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="1"' . (($data['enable']) ? 'checked="checked"' : "") . ' disabled="disabled">';
	    echo '<label for="' . $name . '" class="stackprime-page-setting-disabled">' . $data['description'] . '</label>';
		echo '<p class="description">' . $data['notes'] . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '</table>';
	}

	function stackprime_custom_settings() {
		global $admin_ui, $security, $shortcodes, $performance;
		
		echo '<style>
				.stackprime-page .stackprime-page-description {
					font-size: 15px;
				}
				.stackprime-page h2 {
				    font-size: 1.6em;
    				text-decoration: underline;
				}
				.stackprime-page .form-table th,
				.stackprime-page .form-table td {
					padding: 20px 10px 20px 0;
				}
				.stackprime-page .form-table td p {
					margin-top: 6px;
				}
				.stackprime-page-option-title {
					font-size: 15px;
					font-weight: 700;
				}
				.stackprime-page-setting-disabled {
					margin-left: 10px;
				}
			</style>';
			
		echo '<div class="wrap stackprime-page">';
		echo '<h1>Stackprime settings</h1>';
		echo '<p class="stackprime-page-description">Use the below settings to unbloat your site from unwanted output. Remove admin nags and notifications, unnecessary items and performance-draining code. Please note that some options should only be set if you understand their consequences. If in doubt, leave options unchecked.</p>';
        echo '<hr/>';
		
		echo '<nav class="quick-nav">';
		echo '<p>Jump to section: &nbsp;
				<a href="#stackprime_page_admin_ui">Admin UI</a>
				&bull;
				<a href="#stackprime_page_security">Security</a>
				&bull;
				<a href="#stackprime_page_performance">Performance</a>
				&bull;
				<a href="#stackprime_page_shortcodes">Shortcodes</a>
			</p>
		</nav>';	
              
		echo '<h2 id="stackprime_page_admin_ui">Admin UI improvements</h2>';
		foreach ($admin_ui as $name => $data) {
			print_setting($name, $data);
		}
		
		echo '<h2 id="stackprime_page_security">Security improvements</h2>';
		foreach ($security as $name => $data) {
			print_setting($name, $data);
		}
		
		echo '<h2 id="stackprime_page_performance">Performance improvements</h2>';
		foreach ($performance as $name => $data) {
			print_setting($name, $data);
		}
		
		echo '<h2 id="stackprime_page_shortcodes">Custom shortcodes</h2>';
		foreach ($shortcodes as $name => $data) {
			print_setting($name, $data);
		}
		
            
    	echo '</div>';
    }