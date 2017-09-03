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
		// Cache::flush();
		$this->openstates = new \App\Services\OpenStates;
		$this->civicinfo = new \App\Services\GoogleCivicInfo;

	}

	public function index(Request $request) {
		$lat = null;
		$long = null;
		if ($request->get('addr')) {
			$address = $request->get('addr');
			$cachekey = self::CACHE_PREFIX.':address:'.$address;
			if (!Cache::get($cachekey)) {
				Cache::put($cachekey, $this->getRepresentativesByAddress($address), self::CACHE_TIMEOUT);
			}
			return Cache::get($cachekey);
		}
		return ['error' => 'No address parameter provided'];

	}

	protected function getRepresentativesByAddress($address) {
		$result = $this->civicinfo->getRepresentatives($address);
		$response = collect($result['offices'])->map(function($office) use ($result) {
			return [
				'title' => $office['name'],
				'reps' => isset($office['officialIndices']) ? 
					collect($office['officialIndices'])->map(function($index) use ($result) {
						return $result['officials'][$index];
					}) : 
					[]
			];
		});
		return $response;
	}
}