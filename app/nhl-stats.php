<?php 
/*
Plugin Name: NHL Stats
Description: An unofficial pluign with NHL Stats.
Version: 1.0.0
Author: Ambiente 1
Author URI: https://amb1.tech/nhlstats
License: Copyright
Text Domain: nhlstats
*/

ini_set('memory_limit', '512M');

require 'vendor/autoload.php';

define( 'PLUGIN_VERSION', '1.0.0');
define( 'AMB1_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once(AMB1_PLUGIN_PATH . 'nhl.api.php');
require_once(AMB1_PLUGIN_PATH . 'class.nhlstats.php');

register_activation_hook( __FILE__, array( 'NhlStats', 'plugin_activation' ) );
// register_deactivation_hook( __FILE__, array( 'SeguroSaude', 'plugin_deactivation' ) );
add_action( 'init', array( 'NhlStats', 'init' ) );

if (is_admin()) {
	require_once(AMB1_PLUGIN_PATH . 'admin.actions.php');
	add_action( 'init', array( 'NhlStats_Admin', 'init' ) );
}




