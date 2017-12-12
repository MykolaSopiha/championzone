<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Card;
use DB;
use DataTables;

class APIController extends Controller
{
    public function test()
	{
		return DataTables::eloquent(App\Card::query())->editColumn('code', '{{decrypt($code)}}')->make(true);
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

			$code_hash = sha1($requestData['columns'][2]['search']['value'].config('hidden.salt'));
			$conditions[] = array( 'code_hash', $code_hash );
			$results = DB::table('cards')->where($conditions)->get();
			$totalFiltered = DB::table('cards')->where('code_hash', $code_hash)->count();

		} elseif ( !empty($requestData['search']['value']) && (strlen($requestData['search']['value']) == 16) && is_numeric($requestData['search']['value']) ) {

			$code_hash = sha1($requestData['search']['value'].config('hidden.salt'));
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
			})->addColumn('actions', function($card)
			{
				$acts = "<a href='".url('/home/cards/')."/".$card->id."/edit'>";
				if ($card->status === 'active') {
					$acts .= '<i class="switch fa fa-toggle-on fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
				} else {
					$acts .= '<i class="switch fa fa-toggle-off fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>';
				}
				$acts .= '</a>';

				return $acts."<a class='remove-btn' href=".url('/home/cards/')."/".$card->id.">
							<i class='remove fa fa-times fa-lg' title='Удалить' aria-hidden='true'></i>
						</a>";
			})->make(true);
		}

		foreach ($results as $res) {
			// $res->check = "<input type='checkbox' class='shift_select' name='card[".$card->id."]'>";
			$res->code = decrypt($res->code);
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

			$res->actions .= "<a class='remove-btn' href=".url('/home/cards/')."/".$res->id.">
							<i class='remove fa fa-times fa-lg' title='Удалить' aria-hidden='true'></i>
						</a>";
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
		);

		return $json_data;
	}
}
