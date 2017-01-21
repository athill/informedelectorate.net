<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class StatebillsController extends Controller {

	protected $sunlight;

	public function __construct() {
		$this->sunlight = new \App\Services\Sunlight(env('SUNLIGHT_KEY'));
	}

	public function index(Request $request) {
		$response = $this->sunlight->getStateMetadata();
		return $response;
	}

	public function show(Request $request, $id) {
		return $this->sunlight->getBillsByState($id);
	}
}