<?php

namespace App\Services;

class Propublica extends Api {
	public function __construct() {
		parent::__construct(config('services.api.propublica.key'));
		$this->api_key_param = 'X-API-Key';
		$this->api_key_header = true;
		$this->url = 'https://api.propublica.org/congress/v1';
		$this->separator = '/';	
	} 

	public function getData($method, $params=array()) {
		return parent::get($method, $params);
	}

	public function getCurrentBills() {
		$congress = date('Y') - 1902;	//// 2017 === 115
		$chamber = 'both'; 				//// both|house|senate
		$type = 'active';				//// introduced, updated, active, passed, enacted or vetoed
		$data = $this->getData("/${congress}/${chamber}/bills/${type}.json");
		return $data;
	}

	public function getBillsBySearch($search) {
		return $this->getData("/bills/search.json?query=".urlencode($search));
	}
}
