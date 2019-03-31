<?php
/**
 * Main Plugin File
 */
class NhlStats extends NhlStats_API
{
	use nhlShortcode;

	static function init()
	{
		add_shortcode('nhl-stats', array('NhlStats', 'renderShortcode'));
		add_action('wp_enqueue_scripts', array('NhlStats', 'loadFrontendScripts') );
	}

	function plugin_activation()
	{
		self::getPlayers();
	}

	static function renderShortcode($atts)
	{
		if (array_key_exists('player', $atts)) {
			$playerID = $atts['player'];
			$player = self::getPlayerStats($playerID);

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