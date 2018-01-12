<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;

class ElectionsController extends Controller
{
	const CACHE_PREFIX = 'elections:';
	const CACHE_TIMEOUT = 1440;

	protected $civicinfo;

	public function __construct() {
		$this->civicinfo = new \App\Services\GoogleCivicInfo;
	}

	public function index(Request $request) {
		if ($request->get('addr')) {
			return $this->getElectionInfo($request->get('addr'));
		}

		$cachekey = self::CACHE_PREFIX.'metadata';
		return Cache::remember($cachekey, self::CACHE_TIMEOUT, function() {
			return $this->civicinfo->getUpcomingElections();
		});
	}

	protected function getElectionInfo($address) {
		$cachekey = self::CACHE_PREFIX.$address;
		return Cache::remember($cachekey, self::CACHE_TIMEOUT, function() use ($address) {
			return $this->civicinfo->getElectionInfoByAddress($address);
		});
	}
}
