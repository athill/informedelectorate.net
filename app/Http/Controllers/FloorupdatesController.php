<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FloorupdatesController extends Controller {

	protected $sunlight;

	const CACHE_KEY = 'floorupdates';
	const CACHE_TIMEOUT = 3600;

	public function __construct() {
		$this->sunlight = new \App\Services\Sunlight(env('SUNLIGHT_KEY'));
	}

	public function index(Request $request) {
		if (!Cache::get(self::CACHE_KEY)) {
			Cache::put(self::CACHE_KEY, $this->sunlight->getCurrentFederalFloorUpdates()['results'], self::CACHE_TIMEOUT);
		}
		return Cache::get(self::CACHE_KEY);
	}
}