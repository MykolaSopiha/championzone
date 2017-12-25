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


Route::get('/home/account/{id}',	'UserController@index');
Route::post('/home/account/{id}', 	'UserController@store');


// Route::resource('/home/costs',  'CostController');
Route::resource('/home/tokens', 'TokenController');


Route::resource('/home/accounts',  'AccountController');
Route::resource('/home/cards',  'CardController');
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


Route::get('/setRate',	function ()
{
	$tokens = DB::table('tokens')->get();

	foreach ($tokens as $token) {
		if ($token->rate == 0) {
			switch ($token->currency) {
				case 'USD':
					DB::table('tokens')->where('id', $token->id)->update(['rate' => 1]);
					break;
				
				case 'RUB':
					DB::table('tokens')->where('id', $token->id)->update(['rate' => 0.017077]);
					break;

				case 'EUR':
					DB::table('tokens')->where('id', $token->id)->update(['rate' => 1.185900]);
					break;

				case 'UAH':
					DB::table('tokens')->where('id', $token->id)->update(['rate' => 0.035899]);
					break;

				default:
					break;
			}
		}
	}
	return "Done!";
});