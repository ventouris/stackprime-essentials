<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.stackprime.com
 * @since      1.0.0
 *
 * @package    Stackprime
 * @subpackage Stackprime/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Stackprime
 * @subpackage Stackprime/includes
 * @author     Tasos Ventouris <info@stackprime.com>
 */
class Stackprime_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$shortcodes = get_option('stackprime_shortcodes_options');
		wp_unschedule_event( time(), 'daily', 'get_stock_market_daily_data', array( "company" => $shortcodes["get_stock_market_data_company"]) );
		remove_action('daily', 'remove_update_stock_market');
	}

}
