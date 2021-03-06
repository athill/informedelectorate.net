<?php 
namespace App\Services;

use Log;

class Api {
	protected $url = '';
	protected $key = '';
	public $separator = '.json';
	protected $debug = false;
	protected $curl = null;
	protected $api_key_param = 'apikey';
	protected $api_key_header = false;

	function __construct($key='') {
		if ($key !== '') $this->key = $key;
		$this->curl = new Curl();
	}

	public function getUrl($method, $params=[]) {
		$queryString = $this->getQueryString($params);
		$url = $this->url.$method.$this->separator;
		if (strlen($queryString)) {
			$url .= '?'.$queryString;
		}
		Log::info($url);
		return $url;
	}

	public function getQueryString($params=[]) {

		$qs = ($this->api_key_header) ? [] : [$this->api_key_param.'='.$this->key];
		foreach ($params as $k => $v) {
			if (!is_array($v)) {
				$qs[] = urlencode($k).'='.urlencode($v);
			} else {
				foreach ($v as $value) {
					$qs[] = urlencode($k).'='.urlencode($value);
				}
			}
		}
		return implode('&', $qs);
	}

	public function get($method, $params=array()) {
		$url = $this->getUrl($method, $params);
		$headers = [];
		if ($this->api_key_header) {
			$headers = [$this->api_key_param.': '.$this->key];
		}
		$content = $this->curl->get($url, $headers);
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
