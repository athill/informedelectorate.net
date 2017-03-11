<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use \App\Services\DataGovApi;

class RegulationsController extends Controller {

	protected $api;

	public function __construct() {
		$this->api = new DataGovApi('regulations');
	}

	public function index(Request $request) {
		return $this->api->dockets();
	}
}