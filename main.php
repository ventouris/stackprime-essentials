<?php
/**
 
 * @package Stackprime
 
 */
 
/*
 
Plugin Name: Stackprime Essentials
 
Plugin URI: https://www.stackprime.com/
 
Description: A custom plugin from stackprime to unbloat your site from unwanted output. Remove admin nags and notifications, unnecessary items and performance-draining code. Includes security patches and shortcodes.
 
Version: 0.1.1
 
Author: stackprime
 
Author URI: https://www.stackprime.com/
 
License: GPLv2 or later
 
Text Domain: stackprime
 
*/

	
include( plugin_dir_path( __FILE__ ) . 'options-page.php' );

require_once( plugin_dir_path( __FILE__ ) . 'update.php' );
if ( is_admin() ) {
    new BFIGitHubPluginUpdater( __FILE__, 'myGitHubUsername', "Repo-Name" );
}


