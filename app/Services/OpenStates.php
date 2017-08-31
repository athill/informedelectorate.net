<?php

namespace App\Services;

class OpenStates extends Api {

	public function __construct() {
		parent::__construct(config('services.api.openstates.key'));
		$this->url = 'https://openstates.org/api/v1';
		$this->separator = '/';		
	}

	public function getData($method, $params=array()) {
		return parent::get($method, $params);
	}	

	public function getStateLegislatorsByLatLong($lat, $long) {
		$data = $this->getData('/legislators/geo', array('lat'=>$lat,'long'=>$long));
		return $data;
	}

	public function getStateMetadata() {
		$data = $this->getData('/metadata', array());		
		return $data;
	}

	public function getBillsByState($stateabbrev) {
		$data = $this->getData('/bills', array('state'=>$stateabbrev, 'search_window'=>'term'));
		return $data;
	}	
}
