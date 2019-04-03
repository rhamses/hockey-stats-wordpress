<?php 

trait nhlShortcode {
	public static function renderPlayeTable($player, $league = null)
	{
		$headers = '';
		$cells = '';
		$height = '';
		$weight = '';
		$dominante = '';
		$url = '';
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
			$convertedFoot = explode('\'', $player['infos']->height);
			$height = substr(((intval($convertedFoot[0]) * 0.3048) + (intval($convertedFoot[1]) * 0.0254)), 0, 4) . '<small>m</small>';
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
		if (isset($player['infos']->shootsCatches) && isset($player['infos']->shootsCatches) == "L") {
			$dominante = "Canhoto";
		} elseif(isset($player['infos']->shoots) && $player['infos']->shoots == "L") {
			$dominante = "Canhoto";
		} elseif(isset($player['infos']->catches) && $player['infos']->catches == "L") {
			$dominante = "Canhoto";
		} else {
			$dominante = "Destro";
		}
		/*
			Get player position
		 */
		if (isset($player['infos']->primaryPosition)) {
			
			$abbr = $player['infos']->primaryPosition->abbreviation;
			
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

		if (isset($player['infos']->position)) {
			
			$abbr = $player['infos']->position;

			if ($player['infos']->position == "F") {
				$item = '<dt><b>'.$player['infos']->position.'</b></dt><dd>' . 'Atacante</dd>';
			}

			if ($player['infos']->position == "D") {
				$item = '<dt><b>'.$player['infos']->position.'</b></dt><dd>' . 'Defensor</dd>';
			}

			if ($player['infos']->position == "G") {
				$item = '<dt><b>'.$player['infos']->position.'</b></dt><dd>' . 'Goleiro</dd>';
			}
			
		}
		/*
			Get player image and link
		 */
		switch ($league) {
			case 'nhl':
				$url = 'https://www.nhl.com/player/'.$player['infos']->id;
			break;
			case 'cwhl':
				$url = 'http://thecwhl.com/stats/player/'.$player['infos']->id;
			break;
		}
		$image = '<a target="_new" href="'.$url.'"><figure class="player__image"><img alt="Headshot of '.$player['infos']->fullName.'" src="'.$player['image'].'"></figure></a>';
		/*
			Player Table
		 */
		$playerTable = '<div class="player__stats--table--wrapper"><table class="player__stats--table widefat" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th class="cell--image" rowspan="3">'.$image.'</th>
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
		<h3 class="player__title">#'.$player['infos']->primaryNumber.' '.$player['infos']->fullName.' (<small>'.$abbr.'</small>)</h3>
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

trait PlayerInfo {
	static function statsTable($stats){
		$statsModified = array();
		if (isset($stats->goals)) {
			$statsModified["G"] = array(
				"value" => $stats->goals,
				"legend" => __( 'Total de Gols', 'hockeystats' )
			);
		}

		if (isset($stats->short_handed_goals)) {
			$statsModified["SHG"] = array(
				"value" => $stats->short_handed_goals,
				"legend" => __( 'Gols feitos em desvantagem númerica', 'hockeystats' )
			);
		}

		if (isset($stats->game_winning_goals)) {
			$statsModified["GWG"] = array(
				"value" => $stats->game_winning_goals,
				"legend" => __( 'Total de Gols decisivos', 'hockeystats' )
			);
		}

		if (isset($stats->games_played)) {
			$statsModified["GP"] = array(
				"value" => $stats->games_played,
				"legend" => __( 'Total de Jogos', 'hockeystats' )
			);
		}
		
		if (isset($stats->assists)) {
			$statsModified["A"] = array(
				"value" => $stats->assists,
				"legend" => __( 'Total de Assistências', 'hockeystats' )
			);
		}
		
		if (isset($stats->points)) {
			$statsModified["PTS"] = array(
				"value" => $stats->points,
				"legend" => __( 'Total de Pontos', 'hockeystats' )
			);
		}
		
		if (isset($stats->plus_minus)) {
			$statsModified["+/-"] = array(
				"value" => $stats->plus_minus,
				"legend" => __( 'Total de Mais/Menos', 'hockeystats' )
			);
		}
		
		if (isset($stats->penalty_minutes)) {
			$statsModified["PIM"] = array(
				"value" => $stats->penalty_minutes,
				"legend" => __( 'Tempo total de penalidades', 'hockeystats' )
			);
		}

		if (isset($stats->wins)) {
			$statsModified["W"] = array(
				"value" => $stats->wins,
				"legend" => __( 'Total de Vitórias', 'hockeystats' )
			);
		}

		if (isset($stats->losses)) {
			$statsModified["L"] = array(
				"value" => $stats->losses,
				"legend" => __( 'Total de derrotas', 'hockeystats' )
			);
		}

		if (isset($stats->shutouts)) {
			$statsModified["SHOs"] = array(
				"value" => $stats->shutouts,
				"legend" => __( 'Total de jogos 100% de defesa', 'hockeystats' )
			);
		}

		if (isset($stats->goals_against_average)) {
			$statsModified["GAAV"] = array(
				"value" => $stats->goals_against_average,
				"legend" => __( 'Média total de gols por partida', 'hockeystats' )
			);
		}

		if (isset($stats->savepct)) {
			$statsModified["SV%"] = array(
				"value" => $stats->savepct,
				"legend" => __( 'Pergentagem de defesas feitas', 'hockeystats' )
			);
		}

		return $statsModified;
	}
}

trait PlayerByLeague {
	public static function getPlayerStats($playerID, $league)
	{
		switch ($league) {
			case 'nhl':
				return NhlStats_API::getPlayerStats($playerID, $league);
			break;
			case 'cwhl':
				return CwhlStats_API::getPlayerStats($playerID, $league);
			break;
		}
	}
}