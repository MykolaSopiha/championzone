<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( Auth::user()->status === 'accountant' ) {
            return redirect('/home/tokens');
        } elseif ( Auth::user()->status === 'farmer' ) {
            return redirect('/home/cards');
        } else {
            return view('home');
        }
    }

    public function users()
    {
        if (Auth::user()->status === 'admin')
            return view('home.users');
        return redirect('/home');
    }

    public function statistics(Request $request)
    {
        if (Auth::user()->status === 'farmer')
            return redirect('/home');

        $from = ($request->from != '') ? $request->from : '0000-00-00';
        $to   = ($request->to != '') ? $request->to : date( "Y-m-d" );

        $conditions = [
            [ 'date', '>=', $from ],
            [ 'date', '<=', $to   ]
        ];

        if ($request->card != '')
            $conditions[] = ['card_id', '=', $request->card];

        if ($request->user != '')
            $conditions[] = ['user_id', '=', $request->user];

        $card_conditions = [];
        $statistics = [];
        $total = 0;

        if (Auth::user()->status === 'mediabuyer') {
            $conditions[] = ['user_id', Auth::user()->id];
            $card_conditions[] = ['user_id', Auth::user()->id];
        }

        $tokens = DB::table('tokens')->where($conditions)->join('users', 'tokens.user_id', '=', 'users.id')->select('tokens.*', 'users.name as user_name')->get();
        $users = DB::table('users')->select('id', 'name')->get();
        $cards = DB::table('cards')->select('id', 'name', 'code', 'currency')->where($card_conditions)->get();

        foreach ($tokens as $token) {
            $USD = $token->value*$token->rate/100;
            if ($token->action !== 'deposit') {
                    $USD *= -1;
                }
            if (isset($statistics[$token->date])) {
                $statistics[$token->date]['cost'] += $USD;
            } else {
                $statistics[$token->date] = [
                    'day'  => $token->date, 
                    'cost' => $USD
                ];
            }
            
            $total += $USD;
        }

        return view('home/statistics', compact('statistics', 'total', 'users', 'cards') );
    }

    public function balance()
    {
        return view('home/balance');
    }

    public function motivation()
    {
        return view('home/motivation');
    }
    
    public function wiki()
    {
        return view('home/wiki');
    }

}
