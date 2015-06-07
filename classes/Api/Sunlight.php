<?php  namespace Classes\Api;
class Sunlight extends Api {

	public function getData($collection, $method, $params=array()) {

		$this->setBaseUrl($collection);
		return parent::get($method, $params);

	}

	public function getFederalLegislatorsByLatLong($lat, $long) {
		$data = $this->getData('congress3', '/legislators/locate', array('latitude'=>$lat,'longitude'=>$long));
		return $data;
	}
	public function getStateLegislatorsByLatLong($lat, $long) {
		$data = $this->getData('openstates', '/legislators/geo', array('lat'=>$lat,'long'=>$long));
		return $data;
	}

	public function getCurrentFederalFloorUpdates() {
		$data = $this->getData('congress3', '/floor_updates', array());
		return $data;
	}
	public function getStateMetadata() {
		$data = $this->getData('openstates', '/metadata', array());		
		return $data;
	}

	public function getBillsByState($stateabbrev) {
		$data = $this->getData('openstates', '/bills', array('state'=>$stateabbrev));
		return $data;
	}

	protected function setBaseUrl($collection) {
		switch ($collection) {
			//// deprecated
			case 'congress':
				$this->url = 'http://services.sunlightlabs.com/api/';
				////http://services.sunlightlabs.com/api/api.method.format?apikey=YOUR_API_KEY&<params>
				break;
			case 'congress3':
				$this->url = 'https://congress.api.sunlightfoundation.com';
				$this->separator = '/';
				break;
			case 'openstates':
				$this->url = 'http://openstates.org/api/v1';
				$this->separator = '/';
				////http://openstates.org/api/v1/bills/?{SEARCH-PARAMS}&apikey={YOUR_API_KEY}
				break;
			case 'capitolwords':
				$this->url = 'http://capitolwords.org/api/';
				////http://capitolwords.org/api/dates.json?apikey=<YOUR_KEY>
				break;
			case 'transparencydata':
				$this->url = 'http://transparencydata.com/api/1.0/';
				////http://transparencydata.com/api/1.0/<method>.<format>
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