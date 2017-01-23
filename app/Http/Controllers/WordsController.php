<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WordsController extends Controller {

	protected $sunlight;

	public function __construct() {
		$this->sunlight = new \App\Services\Sunlight(env('SUNLIGHT_KEY'));
	}

	public function index(Request $request) {
		if ($request->get('words')) {
			// return ['??'];
			return $this->sunlight->getData('capitolwords', 'phrases/legislator', array('phrase'=>$request->get('words')));	
		} else if ($request->get('legislator')) {
			return $this->sunlight->getData('congress3', '/legislators', array('bioguide_id'=>$request->get('legislator')));
		}
		// $response = $this->sunlight->getStateMetadata();
		return [];
	}
}