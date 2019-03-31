<?php 
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
/**
 * NHL API WRAPPER 
 */
class NhlStats_API
{
	
	use nhlShortcode;

	static public function searchByPlayer()
	{
		if (get_transient( 'nhlstats_players' )) {
			$players = get_transient( 'nhlstats_players' );
		} else {
			$client = new Client();
			$franchises = self::getFranchises('active');
			$promises = array();
			$players = array();

			foreach ($franchises as $franchise) {
				$promises[$franchise] = $client->getAsync('https://records.nhl.com/site/api/player/byTeam/'.$franchise);
			}

			$results = Promise\unwrap($promises);
			$results = Promise\settle($promises)->wait();

			foreach ($results as $result) {
				$item = json_decode( (string) $result['value']->getBody() );
				foreach ($item->data as $pl) {
					$player = array('id' => $pl->id, 'fullName' => $pl->fullName);
					array_push($players, $player);
				}
			}
			set_transient('nhlstats_players', $players, 60 * 60 * 24 );
		}

		echo json_encode($players);

		die();
	}

	static public function getFranchises($status)
	{
		$client = new GuzzleHttp\Client();
		$res = $client->request('GET', 'https://records.nhl.com/site/api/franchise');
		$clientResponse = $res->getBody();
		$response = array();
		$franchises = json_decode( (string) $clientResponse );
		foreach ($franchises->data as $franchise) {
			if ($status === "active") {
				if (is_null($franchise->lastSeasonId)) {
					array_push($response, $franchise->mostRecentTeamId);
				}
			}
		}
		return $response;
	}

	static public function playerStats()
	{
		// Instance and variables
		$id = $_POST['id'];
		$player = self::getPlayerStats($id);
		$response['data'] = nhlShortcode::renderPlayeTable($player);
		
		echo json_encode($response);

		die();
	}

	static public function getPlayers()
	{
		if (!get_transient( 'nhlstats_players' )) {
			$client = new Client();
			$franchises = self::getFranchises('active');
			$promises = array();
			$players = array();

			foreach ($franchises as $franchise) {
				$promises[$franchise] = $client->getAsync('https://records.nhl.com/site/api/player/byTeam/'.$franchise);
			}

			$results = Promise\unwrap($promises);
			$results = Promise\settle($promises)->wait();

			foreach ($results as $result) {
				$item = json_decode( (string) $result['value']->getBody() );
				foreach ($item->data as $pl) {
					$player = array('id' => $pl->id, 'fullName' => $pl->fullName);
					array_push($players, $player);
				}
			}
			set_transient('nhlstats_players', $players, 60 * 60 * 24 );
		}	
	}

	static public function getPlayerStats($id) 
	{
		// Instance and variables
		$id = $id;
		$client = new GuzzleHttp\Client();
		// promises
		$promises = array();
		$promises['stats'] = $client->getAsync('https://statsapi.web.nhl.com/api/v1/people/' . $id . '/stats?stats=statsSingleSeason');
		$promises['player'] = $client->getAsync('https://statsapi.web.nhl.com/api/v1/people/' . $id);

		$results = Promise\unwrap($promises);
		$results = Promise\settle($promises)->wait();
		// get player infos
		$player = json_decode( (string) $results['player']['value']->getBody() );
		$player = $player->people[0];
		// get current season stats
		$stats = json_decode( (string) $results['stats']['value']->getBody() );
		$stats = $stats->stats[0]->splits[0];
		$statsModified = self::getPlayerStatsByPosition($player->primaryPosition->code, $stats);
		// response
		$response = array();
		$response['season'] = $stats->season;
		$response['stats'] = $statsModified;
		$response['image'] = 'https://nhl.bamcontent.com/images/headshots/current/168x168/'.$id.'.jpg';
		$response['infos'] = $player;

		return $response;
	}

	static public function getPlayerStatsByPosition($position, $stats)
	{
		$statsModified = array();
		if ($position === "G") {
			$statsModified = array(
				"GP" => array(
					"value" => $stats->stat->games,
					"legend" => __( 'Total de Jogos', 'nhl-stats' )
				),
				"W" => array(
					"value" => $stats->stat->wins,
					"legend" => __( 'Total de Vitórias', 'nhl-stats' )
				),
				"L" => array(
					"value" => $stats->stat->losses,
					"legend" => __( 'Total de derrotas', 'nhl-stats' )
				),
				"OT" => array(
					"value" => $stats->stat->ot,
					"legend" => __( 'Total de vitórias na prorrogação', 'nhl-stats' )
				),
				"SHOs" => array(
					"value" => $stats->stat->shutouts,
					"legend" => __( 'Total de jogos 100% de defesa', 'nhl-stats' )
				),
				"SV%" => array(
					"value" => $stats->stat->savePercentage,
					"legend" => __( 'Pergentagem de defesas feitas', 'nhl-stats' )
				),
				"GAAV" => array(
					"value" => $stats->stat->goalAgainstAverage,
					"legend" => __( 'Média total de gols por partida', 'nhl-stats' )
				)
			);
		} else {
			$statsModified = array(
				"GP" => array(
					"value" => $stats->stat->games,
					"legend" => __( 'Total de Jogos', 'nhl-stats' )
				),
				"PTS" => array(
					"value" => $stats->stat->points,
					"legend" => __( 'Total de Pontos', 'nhl-stats' )
				),
				"G" => array(
					"value" => $stats->stat->goals,
					"legend" => __( 'Total de Gols', 'nhl-stats' )
				),
				"A" => array(
					"value" => $stats->stat->assists,
					"legend" => __( 'Total de Assistências', 'nhl-stats' )
				),
				"+/-" => array(
					"value" => $stats->stat->plusMinus,
					"legend" => __( 'Total de Mais/Menos', 'nhl-stats' )
				),
				"TOI" => array(
					"value" => $stats->stat->timeOnIce,
					"legend" => __( 'Tempo ativamente no gelo', 'nhl-stats' )
				),
				"PIM" => array(
					"value" => $stats->stat->pim,
					"legend" => __( 'Tempo total de penalidades', 'nhl-stats' )
				),
				"SHT" => array(
					"value" => $stats->stat->shots,
					"legend" => __( 'Total de disparos ao gol', 'nhl-stats' )
				),
				"HIT" => array(
					"value" => $stats->stat->hits,
					"legend" => __( 'Total de jogadas físicas contra o oponente', 'nhl-stats' )
				),
				"GWG" => array(
					"value" => $stats->stat->gameWinningGoals,
					"legend" => __( 'Total de Gols decisivos', 'nhl-stats' )
				),
				"SH%" => array(
					"value" => $stats->stat->shotPct,
					"legend" => __( 'Percentagem de disparos ao gol', 'nhl-stats' )
				),
			);
		}
		return $statsModified;
	}
}