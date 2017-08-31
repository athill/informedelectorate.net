<?php

namespace App\Services;

class GoogleCivicInfo extends Api {
	// https://www.googleapis.com/civicinfo/v2/representatives?key=AIzaSyDr08tpnUEf6jD1B0w-eETh5RqdPlE11Oc&address=47408
	public function __construct() {
		parent::__construct(config('services.api.google.key'));
		$this->url = 'https://www.googleapis.com/civicinfo/v2';
		$this->separator = '/';	
		$this->api_key_param = 'key';	
	} 

	public function getData($method, $params=array()) {
		return parent::get($method, $params);
	}

	public function getRepresentatives($address) {
		$data = $this->getData('/representatives', ['address'=>$address]);
		return $data;
	}
}