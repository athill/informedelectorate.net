<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use \App\Services\DataGovApi;

class RegulationsController extends Controller {

	const CACHE_KEY = 'regulations';
	const CACHE_TIMEOUT = 3600;
	const API_DATA_GOV_KEY = 'API_DATA_GOV_KEY';

	protected $api;

	public function __construct() {
		$this->api = new DataGovApi(env(self::API_DATA_GOV_KEY), self::CACHE_KEY);
	}

	public function index(Request $request) {
		return $this->api->dockets();
	}
}