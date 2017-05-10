<?php  

namespace App\Services;

class OpenStates extends Api {

	public function __construct() {
		parent::__construct(config('services.api.openstates.key'));
	}

	public function getData($collection, $method, $params=array()) {

		$this->setBaseUrl($collection);
		return parent::get($method, $params);

	}

	public function getStateLegislatorsByLatLong($lat, $long) {
		$data = $this->getData('openstates', '/legislators/geo', array('lat'=>$lat,'long'=>$long));
		return $data;
	}

	public function getStateMetadata() {
		$data = $this->getData('openstates', '/metadata', array());		
		return $data;
	}

	public function getBillsByState($stateabbrev) {
		$data = $this->getData('openstates', '/bills', array('state'=>$stateabbrev, 'search_window'=>'term'));
		return $data;
	}


	protected function setBaseUrl($collection) {
		switch ($collection) {
			case 'openstates':
				$this->url = 'https://openstates.org/api/v1';
				$this->separator = '/';
				break;
			//// deprecated
			case 'realtime':
			default:
				$this->url = 'https://api.realtimecongress.org/api/v1/';
				break;
		}
	}	

	function getFullName($d) {
		$name = $d['first_name'];
		if ($d['middle_name'] != '') $name .= ' '.$d['middle_name'];
		if ($d['nickname'] != '') $name .= ' ('.$d['nickname'].')';
		$name .= ' '.$d['last_name'];		
		return $name;
	}
}