<?php 
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
/**
 * NHL API WRAPPER 
 */
class CwhlStats_API
{
	const firebaseUrl = 'leaguestat-b9523.firebaseio.com';
  const firebaseToken = 'uwM69pPkdUhb0UuVAxM8IcA6pBAzATAxOc8979oJ';
  const firebaseApiKey = 'AIzaSyBVn0Gr6zIFtba-hQy3StkifD8bb7Hi68A';
  const apiKey = 'eb62889ab4dfb04e';
	
	use nhlShortcode;
	use PlayerInfo;

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

	static public function playerStats($id, $league)
	{
		// Instance and variables
		$response = array();
		$player = self::getPlayerStats($id);
		$response['data'] = nhlShortcode::renderPlayeTable($player, $league);
		
		echo json_encode($response);

		die();
	}

	static public function getFranchises()
	{
		$client = new GuzzleHttp\Client();
		$res = $client->request('GET', 'https://lscluster.hockeytech.com/feed/index.php?feed=statviewfeed&view=bootstrap&key=eb62889ab4dfb04e&client_code=cwhl');
		$clientResponse = $res->getBody();
		$response = array();
		$franchises = json_decode( self::cleanResponse($clientResponse, "({", "})" ) );
		foreach ($franchises->teams as $franchise) {
			if ($franchise->id != -1) {
				array_push($response, $franchise->id);
			}
		}
		return $response;
	}

	static public function getPlayers()
	{
		if (!get_transient( 'cwhlstats_players' )) {
			$client = new Client();
			$franchises = self::getFranchises();
			$promises = array();
			$players = array();

			foreach ($franchises as $franchise) {
				$promises[$franchise] = $client->getAsync('https://lscluster.hockeytech.com/feed/index.php?feed=statviewfeed&view=players&team='.$franchise.'&key=eb62889ab4dfb04e&client_code=cwhl');
			}
			$results = Promise\unwrap($promises);
			$results = Promise\settle($promises)->wait();

			foreach ($results as $result) {
				$item = json_decode( self::cleanResponse( $result['value']->getBody(), "([{", "}])" ) );
				$playersList = $item->sections[0]->data;
				foreach ($playersList as $pl) {
					$player = array('id' => $pl->row->player_id, 'fullName' => $pl->row->name);
					array_push($players, $player);
				}
			}
			set_transient('cwhlstats_players', $players, 60 * 60 * 24 );
		};

		$response = array(
			'status' => true,
			'league' => 'cwhl',
			'players' => get_transient( 'cwhlstats_players' )
		);
		
		return $response;
	}

	static public function getPlayerStats($id, $season = "latest") 
	{
		// data format 2014-15 CWHL Regular Season

		// Instance and variables
		$client = new GuzzleHttp\Client();
		// promises
		$promises = array();
		$promises['playerDetails'] = $client->getAsync('https://lscluster.hockeytech.com/feed/index.php?feed=statviewfeed&view=player&player_id='.$id.'&key=eb62889ab4dfb04e&client_code=cwhl');

		$results = Promise\unwrap($promises);
		$results = Promise\settle($promises)->wait();
		// Get player infos (again) and carreer stats (from most recent onwards)
		$playerDetails = json_decode( self::cleanResponse($results['playerDetails']['value']->getBody(), '({', '})') );
		
		if ($playerDetails->info->birthDate) {
			$year = explode('-', $playerDetails->info->birthDate)[0];
			$age = intval(date('Y')) - $year;
		} else {
			$age = null;
		}

		$plInfo = new stdClass();

		$plInfo->id = $id;
		$plInfo->fullName = $playerDetails->info->firstName . ' ' . $playerDetails->info->lastName;
		$plInfo->position = $playerDetails->info->position;
		$plInfo->primaryNumber = $playerDetails->info->jerseyNumber;
		$plInfo->shoots = $playerDetails->info->shoots;
		$plInfo->catches = $playerDetails->info->catches;
		$plInfo->height = $playerDetails->info->height;
		$plInfo->weight = $playerDetails->info->weight;
		$plInfo->currentAge = $age;

		$playerStats = $playerDetails->careerStats[0]->sections[0]->data[0]->row;
		$statsModified = PlayerInfo::statsTable($playerStats);
		if (empty($playerDetails->media)) {
			$image = 'https://placehold.it/168x168';
		} else {
			$image = $playerDetails->media->images[0]->thumb;
		}
		// response
		$response = array();
		$response['season'] = $playerDetails->careerStats[0]->sections[0]->data[0]->row->season_name;
		$response['stats'] = $statsModified;
		$response['image'] = $image;
		$response['infos'] = $plInfo;

		return $response;
	}

	public static function cleanResponse($response, $mask1, $mask2) 
	{
		return str_replace($mask1, '{', str_replace($mask2, '}', (string) $response));
	}
}