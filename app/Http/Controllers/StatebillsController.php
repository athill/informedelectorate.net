<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatebillsController extends Controller {

	private const CACHE_PREFIX = 'statebills:';
	private const CACHE_TIMEOUT = 3600;

	protected $sunlight;

	public function __construct() {
		$this->sunlight = new \App\Services\Sunlight(env('SUNLIGHT_KEY'));
	}

	public function index(Request $request) {
		$cachekey = self::CACHE_PREFIX.'metadata';
		if (!Cache::get($cachekey)) {
			Cache::put($cachekey, $this->sunlight->getStateMetadata(), self::CACHE_TIMEOUT);
		}
		return Cache::get($cachekey);
	}

	public function show(Request $request, $id) {
		$cachekey = self::CACHE_PREFIX.$id;
		if (!Cache::get($cachekey)) {	
			Cache::put($cachekey, $this->sunlight->getBillsByState($id), self::CACHE_TIMEOUT);
		}	
		return Cache::get($cachekey);	
	}
}