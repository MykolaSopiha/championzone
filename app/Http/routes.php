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

Route::get('/home', ['uses' => 'HomeController@index', 'as' => 'home']);

// Resource BEGIN
Route::group(['prefix' => 'home'], function () {
    Route::resource('tokens', 'TokenController');
    Route::resource('cards', 'CardController');
    Route::resource('accounts', 'AccountController');
    Route::resource('costs', 'CostController');
    Route::resource('teams', 'TeamController');

    Route::group(['prefix' => 'users', 'as' => 'home.users.'], function () {
        Route::get('/', ['uses' => 'UserController@index', 'as' => 'index']);
        Route::get('/{id}', ['uses' => 'UserController@show', 'as' => 'view']);
        Route::get('/{id}/edit', ['uses' => 'UserController@edit', 'as' => 'edit']);
        Route::get('/{id}/delete', ['uses' => 'UserController@delete', 'as' => 'delete']);
        Route::post('/{id}', ['uses' => 'UserController@store', 'as' => 'save']);
        Route::get('/ssp', ['uses' => 'UserController@ssp', 'as' => 'ssp']);
    });
});
// Resource END


Route::group(['prefix' => 'home', 'as' => 'home:'], function () {

    Route::get('statistics', 'HomeController@statistics');
    Route::get('balance', 'HomeController@balance');
    Route::get('motivation', 'HomeController@motivation');
    Route::get('wiki', 'HomeController@wiki');

    Route::group(['prefix' => 'cost', 'as' => 'cost.types.'], function () {
        Route::get('types/', ['uses' => 'CostController@costTypes', 'as' => 'index']);
        Route::post('types/', ['uses' => 'CostController@saveType', 'as' => 'save']);
        Route::get('types/{id}/delete', ['uses' => 'CostController@deleteType', 'as' => 'delete']);
    });

    Route::get('multiple', 'CardController@multiplepage');
    Route::post('multiple', 'CardController@multipleadd');
    Route::post('cards/multiple_action', 'CardController@multiple_action');
});

Route::group(['prefix' => 'api'], function () {
    Route::get('test', 'APIController@test');
    Route::get('cards', 'APIController@getCards');
    Route::get('tokens', 'APIController@getTokens');
    Route::get('users', 'APIController@getUsers');

    Route::get('token_notify', 'APIController@checkTokens');
});

Route::get('/wallets', 'CardController@setWallets');
Route::post('/wallets', 'CardController@addWallets');

Route::get('/setroleslist', 'UserController@setroleslist');
Route::get('/setrole', 'UserController@setrole');


Route::get('/bugfix/wallet-encrypt', 'BugFixController@index');