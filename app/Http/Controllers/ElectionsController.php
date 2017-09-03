<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ElectionsController extends Controller
{
	const CACHE_PREFIX = 'elections:';
	const CACHE_TIMEOUT = 3600;

	protected $civicinfo;

	public function __construct() {
		$this->civicinfo = new \App\Services\GoogleCivicInfo;
	}

	public function index(Request $request) {
		if ($request->get('addr')) {
			return $this->getElectionInfo($request->get('addr'));
		}
		$cachekey = self::CACHE_PREFIX.'metadata';
		if (!Cache::get($cachekey)) {
			Cache::put($cachekey, $this->civicinfo->getUpcomingElections(), self::CACHE_TIMEOUT);
		}
		return Cache::get($cachekey);
	}

	protected function getElectionInfo($address) {
		$cachekey = self::CACHE_PREFIX.$address;
		if (!Cache::get($cachekey)) {	
			Cache::put($cachekey, $this->civicinfo->getElectionInfoByAddress($address), self::CACHE_TIMEOUT);
		}	
		return Cache::get($cachekey);
	}
}
