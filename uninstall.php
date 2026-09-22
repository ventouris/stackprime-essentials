<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.stackprime.com
 * @since      1.0.0
 *
 * @package    Stackprime
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$stackprime_options = array(
	'stackprime_admin_ui_options',
	'stackprime_security_options',
	'stackprime_performance_options',
	'stackprime_shortcodes_options',
	'stackprime_woocommerce_options',
	'stackprime_misc_options',
	'stock_market_data',
);

foreach ( $stackprime_options as $stackprime_option ) {
	delete_option( $stackprime_option );
}

delete_site_transient( 'stackprime_github_release' );
wp_unschedule_hook( 'get_stock_market_daily_data' );
