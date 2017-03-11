<?php  
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DataGovApi extends Api {

	protected $url = 'https://api.data.gov/regulations/v3/';
	protected $api_key_param = 'api_key';

	private $count = 0;
	
	const DOCKET = 'docket';
	const DOCKETS = 'dockets';
	const DOCUMENT = 'document';
	const DOCUMENTS = 'documents';	

	//// cache stuff
	protected $cachekey;
	protected $delimiter = ':';
	const CACHE_TIMEOUT = 60 * 24 * 1;


	public function __construct($cachekey = 'datagov') {
		parent::__construct(config('api.datagov'));
		$this->cachekey = $cachekey;
		//// debugging
		// Cache::flush();
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

	public function dockets() {
		//// get documents
		$params = ['rpp' => 100];
		$documents = $this->documents($params);

		$this->count += count($documents);

		$cache = $this->cache(
			[ self::DOCKETS, json_encode($params) ], 
			function() use ($documents) { 
				return $this->getDocketData($documents); 
			}
		);	
		$data = [
			'totalNumRecords' => $documents['totalNumRecords'],
			'dockets' => $this->associateDocumentsWithDockets($cache['documents'], $cache['dockets']),
			'count' => $this->count
		];	

		return $data;
	}	

	protected function mergeDocuments($documents, $documentDetails) {
		foreach ($documentDetails as $i => $details) {
			$document = $documents[$i];
			foreach ($details as $key => $value) {
				if (!isset($document[$key])) {

					$value = $details[$key];
					if (is_array($value) && isset($value['value'])) {
						$value = $value['value'];
					} 
					$documents[$i][$key] = $value;
				}
			}
		}
		return $documents;
	}

	protected function getDocketData($documents) {
		$docketIdKeys = [];
		$documentIds = [];
		$documentDetailUrls = [];
		foreach ($documents['documents'] as $document) {
			//// unique collection of docket ids
			$docketIdKeys[$document['docketId']] = null;
			//// documents
			$documentId = $document['documentId'];
			$documentIds[] = $documentId;
			$documentDetailUrls[] = parent::getUrl('document', ['documentId' => $documentId]);
		}
		$docketIds = array_keys($docketIdKeys);

		//// get docket detail urls
		$docketUrls = [];
		foreach ($docketIds as $id) {
			$params = ['docketId' => $id];
			$docketUrls[] = parent::getUrl('docket', $params);
		}

		///// maybe break these two getMulti's into their own cached methods
		//// dockets
		$dockets = $this->getMulti($docketUrls);
		$this->count += count($dockets);
		//// document details
		$documentDetails = $this->getMulti($documentDetailUrls);
		$this->count += count($documentDetailUrls);

		//// merge documents and document details
		$fullDocuments = $this->mergeDocuments($documents['documents'], $documentDetails);

		return [
			'dockets'=> $dockets,
			'documents' => $fullDocuments
		];
	}

	protected function associateDocumentsWithDockets($documents, $dockets) {
		//// build docket map id=>index
		$docketMap = [];
		foreach ($dockets as $index => $docket) {
			$docketMap[$docket['docketId']] = $index; 
		}
		foreach ($documents as $document) {
			$docketId = $document['docketId'];
			$docketIndex = $docketMap[$docketId];
			if (!isset($docket[$docketIndex]['documents'])) {
				$dockets[$docketIndex]['documents'] = [];
			}
			$dockets[$docketIndex]['documents'][] = $document;
		}
		return $dockets;
	}
	
}
