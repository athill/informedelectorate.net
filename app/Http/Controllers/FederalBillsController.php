<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FederalBillsController extends Controller
{
	const CACHE_PREFIX = 'federalbills:';
	const CACHE_TIMEOUT = 3600;

	protected $propublica;

	public function __construct() {
		$this->propublica = new \App\Services\Propublica;
	}

	public function index(Request $request) {
		Cache::flush();
		if ($request->get('search')) {
			$cachekey = self::CACHE_PREFIX.'current:'.$request->get('search');
			if (!Cache::get($cachekey)) {
				Cache::put($cachekey, $this->propublica->getBillsBySearch($request->get('search')), self::CACHE_TIMEOUT);
			}
			return Cache::get($cachekey);			
		}
		$cachekey = self::CACHE_PREFIX.'current:active';
		if (!Cache::get($cachekey)) {
			Cache::put($cachekey, $this->propublica->getCurrentBills(), self::CACHE_TIMEOUT);
		}
		return Cache::get($cachekey);
	}
}
