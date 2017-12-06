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


Route::get('/home/account/{id}',	'AccountController@index');
Route::post('/home/account/{id}', 	'AccountController@store');


Route::resource('/home/costs',  'CostController');
Route::resource('/home/tokens', 'TokenController');


Route::resource('/home/cards',  'CardController');
Route::post('/home/cards/multiple_action',  'CardController@multiple_action');
Route::get('/home/multiple',  	 'CardController@multiplepage');
Route::post('/home/multiple',  	 'CardController@multipleadd');


Route::get('/api/test', function() {
	return DataTables::eloquent(App\Card::query())->editColumn('code', '{{decrypt($code)}}')->make(true);
});


Route::get('/api/cards', function(Request $request) {

	// storing  request (ie, get/post) global array to a variable
	$requestData= $_REQUEST;

	$columns = array(
		// datatable column index  => database column name
		1 => 'name',
		2 => 'code',
		7 => 'currency',
		5 => 'data',
		9 => 'status'
	);

	// getting total number records without any search
	$totalData = DB::table('cards')->count();
	// when there is no search parameter then total number rows = total number filtered rows.
 
	// getting records as per search parameters
	if( !empty($requestData['columns'][1]['search']['value']) ){   //code
		$results = DB::table('cards')->where('code_hash', sha1("".$requestData['columns'][1]['search']['value'].'mMae68KKqu!SOsIX'))->get();
		$totalFiltered = DB::table('cards')->where('code_hash', sha1("".$requestData['columns'][1]['search']['value'].'mMae68KKqu!SOsIX'))->count();
	} else {
		return DataTables::eloquent(App\Card::query())->editColumn('code', '{{decrypt($code)}}')->make(true);
	}

	foreach ($results as $res) {
		$res->code = decrypt($res->code);
	}

	$json_data = array(
		"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval( $totalData ),  // total number of records
		"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $results   // total data array
	);

	return $json_data;
});