<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RegulationOptionsController extends Controller {

	

	const CACHE_KEY = 'regulationoptions';
	const CACHE_TIMEOUT = 3600;



	public function index(Request $request) {
		return $this->getOptions();
		// if (!Cache::get(self::CACHE_KEY)) {
		// 	Cache::put(self::CACHE_KEY, $this->getOptions(), self::CACHE_TIMEOUT);
		// }
		// return Cache::get(self::CACHE_KEY);
	}

	public function getOptions() {
		$options = [];
		$data = json_decode(file_get_contents('https://raw.githubusercontent.com/regulationsgov/developers/gh-pages/api-docs/documents.json'), true);

		$ignore = ['api_key', 'countsOnly', 'encoded', 'rpp', 'sb', 'po', 'so'];

		foreach ($data['apis'] as $api) {
			if ($api['path'] === '/documents.{response_format}') {
				foreach ($api['operations'][0]['parameters'] as $parameter) {
					if ($parameter['paramType'] === 'query' && !in_array($parameter['name'], $ignore)) {
						$data = [
							'description' => $parameter['description'],
							'type' => $parameter['type'],
							'label' => explode(':', $parameter['description'], 2)[0]
						];
						if ($data['type'] === 'date' && strpos($data['description'], 'date range') !== false) {
							$data['type'] = 'daterange';
						}
						//// parse options
						preg_match_all('/<li>([^<]+)<\/li>/', $parameter['description'], $out);
						if (count($out) === 2) {
							$opts = [];
							foreach ($out[1] as $match) {
									$value = null;
									$display = null;
									if (strpos($match, ':') !== false) {
										[$value, $display] = explode(':', $match);
									} else {
										$value = preg_replace('/^(.*) \(.*/', '$1', $match);
										$display = preg_replace('/.*\((.*)\)/', '$1', $match);
									}
									$opts[] = ['display' => trim($display), 'value' => trim($value)];			
								}	
							$data['options'] = $opts;	
						}
						
						$options[$parameter['name']] = $data;
					}
				}
			}
		}
		return $options;
	}	
}