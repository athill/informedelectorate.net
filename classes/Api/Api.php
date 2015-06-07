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
		$content = $this->getResponse($url);
		// $content = file_get_contents($url);
		if ($this->debug) {
			echo $url.'<br>';
			echo $content.'<br><br>';			
		}

		
		return json_decode($content, true);
	}

	function getResponse($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_VERBOSE, true);
		$content = curl_exec($ch);
		curl_close($ch);
		// echo $url;
		// $content = file_get_contents($url);?
		return $content;		
	}
}
