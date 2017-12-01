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
        if ( Auth::user()->status === 'admin') {
            $cards  = DB::table('cards')->get();
        } else {
            $cards  = DB::table('cards')->where([
                ['status', 'active'],
                ['user_id', Auth::user()->id]
            ])->get();
        }


        if ( Auth::user()->status === 'accountant') {
            $tokens = DB::table('tokens')->get();
        } else {
            $tokens = DB::table('tokens')->where('user_id', Auth::user()->id)->get();
        }

        foreach ($tokens as $token) {
            $user_name = DB::table('users')->where('id', $token->user_id)->limit(1)->get();
            $token->user_name = $user_name[0]->name;

            $card = DB::table('cards')->where('id', $token->card_id)->limit(1)->get();
            $token->card_code = decrypt($card[0]->code);
            $token->card_cw2  = decrypt($card[0]->cw2);

            $token->value = floatval( $token->value/100 );

            switch ($token->action) {
                case 'deposit':
                    $token->action = 'Пополнить';
                    break;

                case 'withdraw':
                    $token->action = 'Списать';
                    break;

                default:
                    # code...
                    break;
            }
        }

        // return dd($tokens);
        return view('home/tokens', compact('cards', 'tokens') );
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
        $token->card_code = decrypt($token->card_code);
        $token->value = $token->value/100; 
        $cards = DB::table('cards')->where('id', $token->card_id)->limit(1)->get();
        $card = $cards[0];
        $statuses = [
            'active',
            'confirmed',
            'trash'
        ];
        return view( 'home/showtoken', compact('token', 'card', 'statuses') );
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
        // DB::update("update tokens set action = ? and ask = ? and ans = ? where id = ?", [
        //     $request->action,
        //     $request->ask,
        //     $request->ans,
        //     $id
        // ]);

        DB::table('tokens')->where('id', $id)->update([
            'action' => $request->action,
            'value'  => intval( round($request["value"], 2)*100 ),
            'ask'    => $request->ask,
            'ans'    => $request->ans,
            'status' => $request->status
        ]);

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
        //
    }
}
