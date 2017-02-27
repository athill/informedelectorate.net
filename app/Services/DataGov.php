<?php  

namespace App\Services;

class DataGov extends Api {

	protected $url = 'https://api.data.gov/regulations/v3/';
	protected $api_key_param = 'api_key';

	function __construct($api_key) {
		parent::__construct($api_key);
		// $this->url = 
	}

	function documents() {
		return parent::get('documents');
	}
}