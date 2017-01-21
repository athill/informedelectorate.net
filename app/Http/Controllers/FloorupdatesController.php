<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class FloorupdatesController extends Controller {

	protected $sunlight;

	public function __construct() {
		$this->sunlight = new \App\Services\Sunlight(env('SUNLIGHT_KEY'));
	}

	public function index(Request $request) {
		$response = $this->sunlight->getCurrentFederalFloorUpdates();
		return $response['results'];
	}
}