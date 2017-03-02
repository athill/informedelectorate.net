<?php  
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DataGovApi extends Api {

	protected $url = 'https://api.data.gov/regulations/v3/';
	protected $api_key_param = 'api_key';
	


	const DOCKET = 'docket';
	const DOCKETS = 'dockets';
	const DOCUMENT = 'document';
	const DOCUMENTS = 'documents';	

	
	//// cache stuff
	protected $cachekey;
	protected $delimiter = ':';
	// const CACHE_TIMEOUT = 60 * 24 * 1;
	const CACHE_TIMEOUT = 1; /// in minutes


	public function __construct($api_key, $cachekey = '') {
		parent::__construct($api_key);
		$this->cachekey = $cachekey;
	}
 
	public function cache($subkeys, $closure, $timeout = self::CACHE_TIMEOUT) {
		if (is_array($subkeys)) {
			$subkeys = implode($this->delimiter, $subkeys);
		}
		$key = $this->cachekey . $this->delimiter . $subkeys;
		$cache = Cache::get($key);
		if (!$cache) {
			Cache::put($key, $closure(), self::CACHE_TIMEOUT);
			$cache = Cache::get($key);
		}
		return $cache;
	}

	public function documents($params=[]) {
		if (!isset($params['daysSinceModified'])) {
			$params['daysSinceModified'] = 0;
		}
		$cache = $this->cache(
			[ self::DOCUMENTS, json_encode($params) ], 
			function() use ($params) { 
				return parent::get('documents', $params); 
			}
		);
		return $cache;
	}



	public function docket($params=[]) {
		if (!isset($params['all']) || !$params['all']) {
			//?daysSinceModified=1
			$params = ['daysSinceModified' => 0];
		}
		return parent::get('docket', $params);
	}	

	public function dockets() {
		//// get documents
		$documents = $this->documents();

		//// get docket ids
		
		$docketIds = array_map(function($doc) {
			return $doc['docketId'];
		}, $documents['documents']);

		return $documents;

		// if (!isset($params['all']) || !$params['all']) {
		// 	//?daysSinceModified=1
		// 	$params = ['daysSinceModified' => 0];
		// }
		// return parent::get('docket', $params);
	}	

	
}



