<?php 

namespace App\Services;

class Api {
	protected $url = '';
	protected $key = '';
	public $separator = '.json';
	protected $debug = false;
	protected $curl = null;

	function __construct($key='') {
		if ($key !== '') $this->key = $key;
		$this->curl = new Curl();
	}

	protected function getUrl($method, $params=array()) {
		$url = $this->url.$method.$this->separator.'?apikey='.$this->key;
		// dd($url);

		foreach ($params as $k => $v) {
			if (!is_array($v)) {
				$url .= '&'.urlencode($k).'='.urlencode($v);
			} else {
				foreach ($v as $value) {
					$url .= '&'.urlencode($k).'='.urlencode($value);
				}
			}
		}
		return $url;
	}

	public function get($method, $params=array()) {
		$url = $this->getUrl($method, $params);
		// print($url);
		$content = $this->curl->get($url);
		// $content = file_get_contents($url);
		if ($this->debug) {
			echo $url.'<br>';
			echo $content.'<br><br>';			
		}
		// return $content;
		return json_decode($content, true);
	}

	public function getMulti($urls) {
		$response = $this->curl->getMulti($urls);
		$results = [];
		foreach ($response as $result) {
			$results[] = json_decode($result, true);		
		}
		return $results;
	}
}
