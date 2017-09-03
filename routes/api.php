<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function () {
	Route::resource('reps', 'RepsController', ['only' => ['index']]);

	Route::resource('elections', 'ElectionsController', ['only' => ['index', 'show']]);

	Route::resource('floorupdates', 'FloorupdatesController', ['only' => ['index']]);

	Route::resource('statebills', 'StatebillsController', ['only' => ['index', 'show']]);

	Route::resource('words', 'WordsController', ['only' => ['index']]);

	Route::resource('regulations', 'RegulationsController', ['only' => ['index']]);

	Route::resource('regulationoptions', 'RegulationOptionsController', ['only' => ['index']]);
	
});

