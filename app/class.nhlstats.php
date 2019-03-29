<?php
/**
 * Main Plugin File
 */
class NhlStats extends NhlStats_API
{
	function init()
	{
		add_shortcode('nhl-stats', array('NhlStats', 'renderShortcode'));
	}

	function plugin_activation()
	{
		require_once(AMB1_PLUGIN_PATH . 'nhl.api.php');
		$api = new NhlStats_API();
		$api->searchByPlayer();
	}

	function renderShortcode($atts)
	{

		if (array_key_exists('player', $atts)) {
			$playerID = $atts['player'];
			$player = self::getPlayerStats($playerID);
			// echo "<pre>";
			// var_dump($player);
			// echo "</pre>";
			// exit();
			// tables
			$headers = '';
			$cells = '';
			foreach ($player['stats'] as $key => $stat) {
				$headers .= '<th>'.$key.'</th>';
				$cells .= '<td title="">'.$stat.'</td>';
			}
			// html
			return '<div class="player--wrapper"><h2 class="player__title">#'.$player['infos']->primaryNumber.' '.$player['infos']->fullName.'</h2>
			<span class="player__title--complementary">'.$player['infos']->shootsCatches.'</span>
			<table class="player__stats" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th rowspan="3">
					<figure class="player__image"><img alt="Headshot of '.$player['infos']->fullName.'" src="'.$player['image'].'"></figure>
				</th>
				'.$headers.'
			</tr>
			<tr>'.$cells.'</tr></table>';
		}
	}
}