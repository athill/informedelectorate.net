<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatebillsController extends Controller {

	const CACHE_PREFIX = 'statebills:';
	const CACHE_TIMEOUT = 3600;

	protected $openstates;

	public function __construct() {
		$this->openstates = new \App\Services\OpenStates();
	}

	public function index(Request $request) {
		$cachekey = self::CACHE_PREFIX.'metadata';
		if (!Cache::get($cachekey)) {
			Cache::put($cachekey, $this->openstates->getStateMetadata(), self::CACHE_TIMEOUT);
		}
		return Cache::get($cachekey);
	}

	public function show(Request $request, $id) {
		$cachekey = self::CACHE_PREFIX.$id;
		if (!Cache::get($cachekey)) {	
			Cache::put($cachekey, $this->openstates->getBillsByState($id), self::CACHE_TIMEOUT);
		}	
		return Cache::get($cachekey);	
	}
}