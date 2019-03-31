<?php 
/*
Plugin Name: Hockey Stats
Description: Generate easy tables with stats of your favorite team or player in Hockey. Just copy and paste the generated shortcode inside in your post and boom! Instant and custom rich media information for your site.
Version: 1.0.8
Author: Ambiente 1
Author URI: https://ambiente1.com.br
License: GPLv3
Text Domain: hockeystat 
*/

ini_set('memory_limit', '512M');

require 'vendor/autoload.php';

define( 'PLUGIN_VERSION', '1.0.8');
define( 'AMB1_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once(AMB1_PLUGIN_PATH . 'classes/trait.plugin.php');
require_once(AMB1_PLUGIN_PATH . 'classes/nhl.api.php');
require_once(AMB1_PLUGIN_PATH . 'classes/settings.plugin.php');

add_action( 'init', array( 'NhlStats', 'init' ) );

if (is_admin()) {
	require_once(AMB1_PLUGIN_PATH . 'classes/admin.settings.php');
	add_action( 'init', array( 'NhlStats_Admin', 'init' ) );
}