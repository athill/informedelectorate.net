<?php namespace Classes\Api;
class Api {
	protected $url = '';
	protected $key = '';
	public $separator = '.json';
	protected $debug = false;
	protected $curl = null;

	function __construct($key='') {
		if ($key !== '') $this->key = $key;
		$this->curl = new \Classes\Curl();
	}

	protected function getUrl($method, $params=array()) {
		$url = $this->url.$method.$this->separator.'?apikey='.$this->key;
		// echo $url;
		foreach ($params as $k => $v) {
			if (!is_array($v)) {
				$url .= '&'.urlencode($k).'='.urlencode($v);
			} else {
				foreach ($v as $value) {
					$url .= '&'.urlencode($k).'='.urlencode($value);
				}
				//$url .= '&all_legislators=1';
			}
		}
		 // echo $url;
		return $url;
	}

	public function get($method, $params=array()) {
		$url = $this->getUrl($method, $params);
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
