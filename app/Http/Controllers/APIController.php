<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Card;
use App\User;
use DB;
use DataTables;

class APIController extends Controller
{
    public function test()
	{
		return DataTables::eloquent(App\Card::query())->editColumn('code', '{{decrypt($code)}}')->make(true);
	}

	public function getUsers() {
		$users = User::select('id','name', 'first_name', 'last_name', 'terra_id', 'status', 'created_at');
		return DataTables::of($users)->addColumn('edit', function($user)
			{
				if ( is_null($user->terra_id) ) $user->terra_id = '';
				return "<a href='".url('/home/account/')."/"."$user->id'><i class='fa fa-pencil' aria-hidden='true'></i></a>";
			})->make(true);
	}

	public function getCards(Request $request)
	{

		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;

		// getting total number records without any search
		$totalData = DB::table('cards')->count();
		// when there is no search parameter then total number rows = total number filtered rows.
	 
		$conditions = [];

		// getting records as per search parameters
		if( !empty($requestData['columns'][2]['search']['value']) ){   //code

			$code_hash = sha1($requestData['columns'][2]['search']['value'].env('APP_SALT'));
			$conditions[] = array( 'code_hash', $code_hash );
			$results = DB::table('cards')->where($conditions)->get();
			$totalFiltered = DB::table('cards')->where('code_hash', $code_hash)->count();

		} elseif ( !empty($requestData['search']['value']) && (strlen($requestData['search']['value']) == 16) && is_numeric($requestData['search']['value']) ) {

			$code_hash = sha1($requestData['search']['value'].env('APP_SALT'));
			$conditions[] = array( 'code_hash', $code_hash );
			$results = DB::table('cards')->where($conditions)->get();
			$totalFiltered = DB::table('cards')->where('code_hash', $code_hash)->count();

		} else {
			$cards = Card::all();
			return DataTables::of($cards)->editColumn('code', '{{decrypt($code)}}')->addColumn('check', function ($card)
			{
				return "<input type='checkbox' class='shift_select' name='card[".$card->id."]'>";
			}, 0)->editColumn('user_id', function($card)
			{
				$user = DB::table('users')->where('id', $card->user_id)->limit(1)->get();
				$user = $user[0];
				return "<a href='".url('home/cards')."/".$card->id."'>".$user->name."</a>";
			})->editColumn('date', function($card)
			{
				return $card->date = substr($card->date, 0, 4)."-".substr($card->date, -2);
			})->addColumn('actions', function($card)
			{
				$acts = "<a href='".url('/home/cards/')."/".$card->id."/edit'>";
				if ($card->status === 'active') {
					$acts .= '<i class="switch fa fa-toggle-on fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
				} else {
					$acts .= '<i class="switch fa fa-toggle-off fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
				}
				$acts .= '</a>';

				return $acts."<a class='remove-btn' href=".url('/home/cards/')."/".$card->id."><i class='remove fa fa-times fa-lg' title='Удалить' aria-hidden='true'></i></a>";
			})->make(true);
		}

		foreach ($results as $res) {
			// $res->check = "<input type='checkbox' class='shift_select' name='card[".$card->id."]'>";
			$res->code = decrypt($res->code);
			$res->date = substr($res->date, 0, 4)."-".substr($res->date, -2);
			$res->check = "<input type='checkbox' class='shift_select' name='card[26]'>";
			$user = DB::table('users')->where('id', $res->user_id)->limit(1)->get();
			$user = $user[0];
			$res->user_id = "<a href='".url('home/cards')."/".$res->id."'>".$user->name."</a>";

			$acts = "<a href='".url('/home/cards/')."/".$res->id."/edit'>";
			if ($res->status === 'active') {
				$acts .= '<i class="switch fa fa-toggle-on fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
			} else {
				$acts .= '<i class="switch fa fa-toggle-off fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
			}
			$acts .= '</a>';

			$res->actions = $acts;

			$res->actions .= "<a class='remove-btn' href=".url('/home/cards/')."/".$res->id."><i class='remove fa fa-times fa-lg' title='Удалить' aria-hidden='true'></i></a>";
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
		);

		return $json_data;
	}



	public function tokensNotification(Request $request)
	{
		if (Auth::user()->status === "accountant") {
			return DB::table('tokens')->where('status', 'active')->count();
		}
		return DB::table('tokens')->where([
			'user_id' => Auth::user()->id,
			'status'  => 'confirmed'
		])->count();
	}



	public function createLead(Request $request)
	{

		if (!isset($_POST['name']) || !isset($_POST['phone']))
			if (isset($_SERVER['HTTP_REFERER']))
				header("Location: ".$_SERVER['HTTP_REFERER']);
			else
				header("Location: /");

		$data = json_decode(array_keys(json_decode($_POST['result'], true))[0]);
        
		$lead = new Lead();
		$lead->fill([
			'offer_id' => $data->offer_id,
			'stream_id' => $data->stream_id,
			'user_id' => $data->user_id,
			'name' => $data->name,
			'phone' => $data->phone,
			'tz' => $data->tz,
			'address' => $data->address,
			'country' => $data->country,
			'utm_source' => $data->utm_source,
			'utm_medium' => $data->utm_medium,
			'utm_campaign' => $data->utm_campaign,
			'utm_term' => $data->utm_term,
			'utm_content' => $data->utm_content,
			'check_sum' => $data->check_sum
		]);
		// $card->save();
		return 500;

	}

}
