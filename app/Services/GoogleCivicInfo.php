<?php

namespace App\Services;

class GoogleCivicInfo extends Api {
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

	public function getUpcomingElections() {
		return $this->getData('/elections');
	}

	public function getElectionInfoByAddress($address) {
		$result = $this->getData('/voterinfo', ['address' => $address]);
		return $result;
	}
}
