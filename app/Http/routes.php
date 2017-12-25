<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 			'HomeController@index');
Route::get('/home/users', 		'HomeController@users');
Route::get('/home/statistics', 	'HomeController@statistics');
Route::post('/home/statistics', 'HomeController@date_range');
Route::get('/home/balance', 	'HomeController@balance');
Route::get('/home/motivation',  'HomeController@motivation');
Route::get('/home/wiki', 		'HomeController@wiki');


Route::get('/home/users/{id}',	'UserController@index');
Route::post('/home/users/{id}', 'UserController@store');

Route::resource('/home/tokens', 'TokenController');
Route::resource('/home/cards',  'CardController');
Route::resource('/home/accounts',  'AccountController');

Route::post('/home/cards/multiple_action',  'CardController@multiple_action');
Route::get('/home/multiple',  	 'CardController@multiplepage');
Route::post('/home/multiple',  	 'CardController@multipleadd');


Route::get('/api/test',  		'APIController@test');
Route::get('/api/users',		'APIController@getUsers');
Route::get('/api/cards',		'APIController@getCards');
Route::get('/api/tokens',		'APIController@getTokens');
Route::any('/api/lead/create',	'APIController@createLead');
Route::any('/api/lead/update',	'APIController@updateLead');
Route::any('/api/lead/status', 	'APIController@statusLead');
Route::get('/api/token_notify',	'APIController@checkTokens');