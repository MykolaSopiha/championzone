<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;
use App\Token;
use DB;
use Auth;

class TokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $cards_permission = [];
        if ( Auth::user()->status !== 'admin') {
                $cards_coditions[] = ['status', 'active'];
                $cards_coditions[] = ['user_id', Auth::user()->id];
        }
        $cards = DB::table('cards')->where($cards_permission)->get();

        return view('home/tokens', compact('cards'));
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
            'card'  => 'required|numeric|min:1'
        ]);

        $token_card = DB::table('cards')->where('id', $request["card"])->limit(1)->get();
        $token_card = $token_card[0];

        if ( !isset($request['ask']) ) {
            $request['ask'] = '';
        }

        if ( !isset($request['ans']) ) {
            $request['ans'] = '';
        }

        $token = new Token();
        $token->fill([
            'date'      => date("Y-m-d"),
            'user_id'   => intval(Auth::user()->id),
            'card_id'   => $request["card"],
            'card_code' => $token_card->code,
            'value'     => intval( round($request["value"], 2)*100 ),
            'currency'  => $token_card->currency,
            'rate'      => $request['rate'],
            'action'    => $request['action'],
            'ask'       => $request['ask'],
            'ans'       => $request['ans'],
            'status'    => 'active'
        ]);
        $token->save();

        return redirect('/home/tokens');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $token = DB::table('tokens')->where('id', $id)->limit(1)->get();
        $token = $token[0];


        if (Auth::user()->id == $token->user_id || Auth::user()->status == 'accountant' || Auth::user()->status == 'admin') {            

            $token->card_code = decrypt($token->card_code);
            $token->value = $token->value/100; 
            $cards = DB::table('cards')->where('id', $token->card_id)->limit(1)->get();
            $card = $cards[0];
            $statuses = [
                'active',
                'confirmed',
                'trash'
            ];
            return view( 'home.show.token', compact('token', 'card', 'statuses') );
        } else {
            return redirect('/home/tokens');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = DB::table('tokens')->where('id', $id)->limit(1)->get();
        $token = $token[0];

        if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' || ($token->user_id === Auth::user()->id))
        if (isset($_GET['status'])) {

            $status = $_GET['status'];
            
            switch ($status) {
                case 'active':
                    $token = DB::table('tokens')->where('id', $id)->limit(1)->update(['status' => 'active']);
                    break;
                case 'confirmed':
                    $token = DB::table('tokens')->where('id', $id)->limit(1)->update(['status' => 'confirmed']);
                    break;
                case 'trash':
                    $token = DB::table('tokens')->where('id', $id)->limit(1)->update(['status' => 'trash']);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return redirect('/home/tokens');
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
        $request["value"] = intval( round($request["value"], 2)*100 );
        
        DB::table('tokens')->where('id', $id)->update(request()->except([
            '_token',
            '_method'
        ]));

        return redirect('/home/tokens');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ( is_array($id) ) {
            foreach ($id as $i) {
                DB::table('tokens')->where([
                    ['id', $i],
                    ['status', 'active']
                ])->delete();
            }
        } else {
            DB::table('tokens')->where([
                ['id', $id],
                ['status', 'active']
            ])->delete();
        }
        return redirect('/home/tokens');
    }
}
