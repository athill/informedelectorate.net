<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RepsController extends Controller {

	protected $sunlight;
	protected $openstates;

	const CACHE_PREFIX = 'reps:';
	const CACHE_TIMEOUT = 3600;	

	public function __construct() {
		$this->sunlight = new \App\Services\Sunlight();
		$this->openstates = new \App\Services\OpenStates();
	}

	public function index(Request $request) {
		$lat = null;
		$long = null;
		if ($request->get('addr')) {
			$addr = $request->get('addr');
			return $this->getRepresentativesByAddr($addr);
			$cachekey = self::CACHE_PREFIX.':addr:'.$addr;
			if (!Cache::get($cachekey)) {
				Cache::put($cachekey, $this->getRepresentativesByAddr($addr), self::CACHE_TIMEOUT);
			}
			return Cache::get($cachekey);
		}
		return ['error' => 'No addr parameter provided'];

	}

	protected function getRepresentativesByAddr($addr) {
		$json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($addr).'&sensor=false');
		$result = json_decode($json, true);
		if ($result['status'] === 'ZERO_RESULTS') {
			return ['error' => 'Cannot find address ['.$addr.']'];
		}		
		// return $result;
		$location = $result['results'][0]['geometry']['location'];
		return $this->getRepresentatives($location['lat'], $location['lng']);
	}

	protected function getRepresentatives($lat, $long) {
		$fedResponse = $this->sunlight->getFederalLegislatorsByLatLong($lat, $long);
		// dd($fedResponse);

		$fed = collect($fedResponse['results'])->map(function($item) {
			return $this->getFederalLegislator($item);
		});


		$stateResponse = $this->openstates->getStateLegislatorsByLatLong($lat, $long);

		$state = collect($stateResponse)->map(function($item) {
			return $this->getStateLegislator($item);
		});

		return ['fed' => $fed, 'state' => $state];		
	}

	protected function getFederalLegislator($item) {
		$fields = [
			'Name' => $this->sunlight->getFullName($item),
			'Chamber' => ucfirst($item['chamber']),
			'District' => $item['district'],
			'Party' => $item['party'],
			'Address' => $item['office'],
			'Website' => $item['website'],
			'Phone' => $item['phone'],
			'YouTube' => isset($item['youtube_id']) ? 'https://www.youtube.com/user/'.$item['youtube_id'] : '',
			'OpenCongress' => 'http://www.opencongress.org/people/show/'.$item['govtrack_id'],
			'Twitter' => isset($item['twitter_id']) ? 'https://twitter.com/'.$item['twitter_id'] : '',
			'Facebook' => isset($item['facebook_id']) ? 'https://www.facebook.com/'.$item['facebook_id'] : ''
		];
		return $fields;
	}

	protected function getStateLegislator($leg) {
		$committees = array();
		foreach ($leg['roles'] as $role) {
			if (array_key_exists('committee', $role)) {
				$committees[] = $role['committee'];
			}
		}
		$fields = array(
			'<img src="'.$leg['photo_url'].'" width="50" />'=>$leg['full_name'],
			'District'=>$leg['district'],
			'Party'=>$leg['party'],
			'Website'=> isset($leg['url']) ? $leg['url'] : '',
			'Chamber'=>ucfirst($leg['chamber']),
			'Phone'=>$leg['offices'][0]['phone'],
			'Committees'=>implode(',', $committees)
		);			
		return $fields;
	}
}