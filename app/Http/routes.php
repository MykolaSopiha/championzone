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


Route::get('/home', 'HomeController@index');


Route::group(['prefix' => 'home'], function () {
    Route::get('users', 		   'UserController@index');
    Route::get('users/{id}',	   'UserController@show');
    Route::get('users/{id}/edit',  'UserController@edit');
    Route::post('users/{id}',      'UserController@store');
    Route::get('users/ssp',		   'UserController@ssp');

    Route::get('statistics', 	'HomeController@statistics');
    Route::get('balance', 	    'HomeController@balance');
    Route::get('motivation',    'HomeController@motivation');
    Route::get('wiki', 		    'HomeController@wiki');
    Route::post('statistics',   'HomeController@date_range');

    Route::resource('tokens',    'TokenController');
    Route::resource('cards',     'CardController');
    Route::resource('costs',     'CostController');
    Route::resource('accounts',  'AccountController');

    Route::get('multiple',  	 'CardController@multiplepage');
    Route::post('multiple',  	 'CardController@multipleadd');
    Route::post('cards/multiple_action',  'CardController@multiple_action');
});


Route::group(['prefix' => 'api'], function () {
    Route::get('test',  		'APIController@test');
    Route::get('cards',		    'APIController@getCards');
    Route::get('tokens',		'APIController@getTokens');
    Route::get('users', 		'APIController@getUsers');


    Route::any('lead/create',      'LeadController@store');
    Route::any('lead/tl_create',   'LeadController@tl_create');
    Route::any('lead/status',      'LeadController@status');
    Route::any('lead/postback',    'LeadController@postback');
    Route::get('token_notify',	'APIController@checkTokens');
});


Route::get('/wallets',  'CardController@setWallets');
Route::post('/wallets', 'CardController@addWallets');
