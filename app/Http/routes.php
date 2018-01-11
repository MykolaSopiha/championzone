<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Http\Request;
use App\Http\Requests;

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


Route::get('/api/token_notify',	'APIController@checkTokens');


Route::any('/api/lead/create',	'APIController@createLead');
Route::any('/api/lead/update',	'APIController@updateLead');
Route::any('/api/lead/status', 	'APIController@statusLead');

Route::get('/wallets', function() {
	return view('home.wallets');
});

Route::post('/wallets', function(Request $request) {

	$text  = preg_replace('/[ ]{2,}|[\t]|[\r]/', ' ', trim($request->text));
	$strings = explode("\n", $text);

	$errors = [];

	foreach ($strings as $str) {

		$word = explode(' ', $str);
		$errors[$str] = [];


		// Validation BEGIN
		if (strlen($word[0]) != 15) {
			$errors[$str][] = 'Wallet code is incorrect!';
		}

		if (strlen($word[1]) != 16) {
			if (strlen($word[1].$word[2].$word[3].$word[4]) == 16) {
				$word[1] = $word[1].$word[2].$word[3].$word[4];
			} else {
				$errors[$str][] = 'Card code is incorrect!';
			}
		}

		if (!is_numeric($word[0])) {
			$errors[$str][] = 'Wallet code is not numeric!';
		}

		if (!is_numeric($word[1])) {
			$errors[$str][] = 'Card code is not numeric!';
		}

		if (DB::table('cards')->where('code_hash', sha1($word[1].env('APP_SALT')))->count() == 0) {
			$errors[$str][] = 'Card not found!';
		}
		// Validation END


		foreach ($errors as &$err) {
			if (empty($err)) {
				DB::table('cards')->where('code_hash', sha1($word[1].env('APP_SALT')))->update(['wallet' => $word[0]]);
				$err = 'done!';
			}

		}

	}
	return dd($errors);

});