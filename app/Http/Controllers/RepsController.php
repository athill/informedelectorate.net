<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RepsController extends Controller {

	protected $sunlight;

	private const CACHE_PREFIX = 'reps:';
	private const CACHE_TIMEOUT = 3600;	

	public function __construct() {
		$this->sunlight = new \App\Services\Sunlight(env('SUNLIGHT_KEY'));
	}

	public function index(Request $request) {
		$lat = $request->get('lat');
		$long = $request->get('long');

		if (is_null($lat) || is_null($long)) {
			return ['error' => 'lat and long arguments are required'];
		}
		$cachekey = self::CACHE_PREFIX.$lat.'x'.$long;

		if (!Cache::get($cachekey)) {
			Cache::put($cachekey, $this->getRepresentatives($lat, $long), self::CACHE_TIMEOUT);
		}
		return Cache::get($cachekey);

	}

	protected function getRepresentatives($lat, $long) {
		$fedResponse = $this->sunlight->getFederalLegislatorsByLatLong($lat, $long);
		// dd($fedResponse);

		$fed = collect($fedResponse['results'])->map(function($item) {
			return $this->getFederalLegislator($item);
		});


		$stateResponse = $this->sunlight->getStateLegislatorsByLatLong($lat, $long);

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