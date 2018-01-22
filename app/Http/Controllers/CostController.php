<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Cost;
use Auth;
use DB;
use View;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $currencies = config('assets.currencies');
        View::share(compact('currencies'));
    }

    public function index()
    {
        if (Auth::user()->status === 'admin') {
            $costs = DB::select("select * from costs");
            $cards = DB::select("select * from cards");
            $users = DB::select("select * from users");
        } else {
            $costs = DB::select("select * from costs where user_id = ?", [Auth::user()->id]);
            $cards = DB::select("select * from cards where user_id = ?", [Auth::user()->id]);
            $users = DB::select("select * from users where id = ?",      [Auth::user()->id]);
        }
        foreach ($costs as $cost) {
            $cost->value = $cost->value/100;
            $cost->card_name = "";
            $cost->currency  = "";
            $cost->card_code = "";
            foreach ($cards as $card) {
                if ($cost->card_id == $card->id) {
                    $cost->card_name = $card->name;
                    $cost->card_code = decrypt($card->code);
                    $cost->currency = $card->currency;
                    break;
                }
            }
        }
        foreach ($costs as $cost) {
            foreach ($users as $user) {
                if ($cost->user_id == $user->id) {
                    $cost->user_name = $user->name;
                    break;
                }
            }
        }
        //return dd( compact('costs', 'cards') );
        return view('home/costs', compact('costs', 'cards') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'date'  => 'required|date',
            'card'  => 'sometimes|numeric|min:1',
            'value' => 'required|numeric',
            'rate'  => 'required|numeric',
            'info'  => 'required'
        ]);

        $cost = new Cost();
        $cost->fill([
            'date'      => date( "Y-m-d", strtotime($request["date"]) ),
            'card_id'   => 0,
            'value'     => intval( round($request["value"], 2)*100 ),
            'rate'      => $request["rate"],
            'user_id'   => Auth::user()->id,
            'info'      => $request["info"]
        ]);
        $cost->save();

        return redirect('/home/costs');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cost::find($id)->destroy($id);
        return redirect('home/costs');
    }
}
