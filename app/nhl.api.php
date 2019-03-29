<?php 
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
/**
 * NHL API WRAPPER 
 */
class NhlStats_API
{
	public function searchByPlayer()
	{
		if (get_transient( 'players' )) {
			$players = get_transient( 'players' );
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
			set_transient('players', $players, 60 * 60 * 24 );
		}

		echo json_encode($players);

		die();
	}

	public function getFranchises($status)
	{
		$client = new GuzzleHttp\Client();
		$res = $client->request('GET', 'https://records.nhl.com/site/api/franchise');
		$clientResponse = $res->getBody();
		$response = array();
		$franchises = json_decode( (string) $clientResponse );
		foreach ($franchises->data as $franchise) {
			if ($status === "active") {
				if (is_null($franchise->lastSeasonId)) {
					array_push($response, $franchise->id);
				}
			}
		}
		return $response;
	}

	public function playerStats()
	{
		// Instance and variables
		$id = $_POST['id'];
		$response = self::getPlayerStats($id);

		echo json_encode($response);

		die();
	}

	public function getPlayerStats($id) 
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
		$statsModified = array(
			"GP" => $stats->stat->games,
			"Pts" => $stats->stat->points,
			"G" => $stats->stat->goals,
			"A" => $stats->stat->assists,
			"+/-" => $stats->stat->plusMinus,
			"TOI" => $stats->stat->timeOnIce,
			"PIM" => $stats->stat->pim,
			"S" => $stats->stat->shots,
			"H" => $stats->stat->hits,
			"GWG" => $stats->stat->gameWinningGoals,
			"Sh%" => $stats->stat->shotPct
		);
		// rsponse
		$response = array();
		$response['season'] = $stats->season;
		// $response['stats'] = $stats->stat;
		$response['stats'] = $statsModified;
		$response['image'] = 'https://nhl.bamcontent.com/images/headshots/current/168x168/'.$id.'.jpg';
		$response['infos'] = $player;

		return $response;
	}
}