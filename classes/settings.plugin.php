<?php
/**
 * Main Plugin File
 */
class NhlStats
{
	use nhlShortcode;
	use PlayerByLeague;

	static function init()
	{
		add_shortcode('nhl-stats', array('NhlStats', 'renderShortcode'));
		add_action('wp_enqueue_scripts', array('NhlStats', 'loadFrontendScripts') );
	}
	
	static function renderShortcode($atts)
	{
		if (array_key_exists('player', $atts) && array_key_exists('league', $atts)) {
			$playerID = $atts['player'];
			$league = $atts['league'];
			$player = PlayerByLeague::getPlayerStats($playerID, $league);
			// $player = self::getPlayerStats($playerID, $league);
			return nhlShortcode::renderPlayeTable($player);
		}
	}

	static function loadFrontendScripts()
	{
		wp_register_style( 'nhlstats-css', plugin_dir_url( __FILE__ ) . '../frontend/css/nhlstats.css', null, PLUGIN_VERSION);
		wp_enqueue_style('nhlstats-css');

		wp_register_script( 'nhlstats-js', plugin_dir_url( __FILE__ ) . '../frontend/js/nhlstats.js', null, PLUGIN_VERSION, true );
		wp_enqueue_script( 'nhlstats-js' );
	}
}
