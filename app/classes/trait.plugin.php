<?php 

trait nhlShortcode {
	public static function renderPlayeTable($player)
	{
		$headers = '';
		$cells = '';
		$height = '';
		$weight = '';
		$dominante = '';
		$legend = array();
		$position = array();

		$metricSystem = get_option('nhlstats_metricsystem');

		foreach ($player['stats'] as $key => $stat) {
			// prepare html legend box
			$item = '<dt><b>'.$key.'</b> </dt>' . '<dd>' . $stat['legend'] . '</dd>';
			array_push($legend, $item);
			// create headers and cells from player table
			$headers .= '<th title="'.$stat['legend'].'">'.$key.'</th>';
			$cells .= '<td title="'.$stat['legend'].'">'.$stat['value'].'</td>';
		}
		if ($metricSystem == "M") {
			/*
				Convert FOOT to M
			 */
			$height = substr((intval(str_replace('\'', '', $player['infos']->height)) * 0.3048), 0, 4) . '<small>m</small>';
			/*
				Convert LBS to KG
			 */
			$weight = substr(($player['infos']->weight * 0.453592), 0, 5) . '<small>kg</small>';
		} else {
			$height = $player['infos']->height;
			$weight = $player['infos']->weight . ' <small>lbs</small>';
		}
		/*
			Destro ou Canhoto
		 */
		if ($player['infos']->shootsCatches == "L") {
			$dominante = "Canhoto";
		} else {
			$dominante = "Destro";
		}
		/*
			Get player position
		 */
		if ($player['infos']->primaryPosition) {
			switch ($player['infos']->primaryPosition->abbreviation) {
				case 'LW':
					$item = '<dt><b>'.$player['infos']->primaryPosition->abbreviation.'</b></dt><dd>' . 'Ala esquerda</dd>';
				break;
				case 'RW':
					$item = '<dt><b>'.$player['infos']->primaryPosition->abbreviation.'</b></dt><dd>' . 'Ala direita</dd>';
				break;
				case 'C':
					$item = '<dt><b>'.$player['infos']->primaryPosition->abbreviation.'</b></dt><dd>' . 'Central</dd>';
				break;
				case 'G':
					$item = '<dt><b>'.$player['infos']->primaryPosition->abbreviation.'</b></dt><dd>' . 'Goleiro</dd>';
				break;
				case 'D':
					$item = '<dt><b>'.$player['infos']->primaryPosition->abbreviation.'</b></dt><dd>' . 'Defensor</dd>';
				break;
			}
			array_push($legend, $item);
		}
		/*
			Player Table
		 */
		$playerTable = '<div class="player__stats--table--wrapper"><table class="player__stats--table widefat" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th class="cell--image" rowspan="3">
				<a target="_new" href="https://www.nhl.com/player/'.$player['infos']->id.'">
					<figure class="player__image"><img alt="Headshot of '.$player['infos']->fullName.'" src="'.$player['image'].'"></figure>
				</a>
			</th>
			'.$headers.'
		</tr>
		<tr>'.$cells.'</tr></table></div>';
		/*
			Box de legenda
		 */
		
		$legendHtml = '<div class="player__stats--legend--wrapper">
		<dl class="player__stats--legend__list">'.implode('', $legend).'</dl></div>';
		// html
		return '<div class="player--wrapper">
		<h3 class="player__title">#'.$player['infos']->primaryNumber.' '.$player['infos']->fullName.' (<small>'.$player['infos']->primaryPosition->abbreviation.'</small>)</h3>
		<p class="player__title--complementary">
		<span><b>Idade: </b>'.$player['infos']->currentAge.'</span>
		<span><b>Altura: </b>'.$height.'</span>
		<span><b>Peso: </b>'.$weight.'</span>
		<span><b>Mão dominante: </b>'.$dominante.'</span>
		</p>'.$playerTable.$legendHtml.'</div>';
	}
}

trait middleware {
	static public function checkTransient($view, $as){
		var_dump($as);
		exit();
		return require(AMB1_PLUGIN_PATH . 'views/'.$view);
	}
}