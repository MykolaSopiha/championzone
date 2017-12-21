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

    public function statistics()
    {
        $stat = [];
        $total = 0;

        $user_costs = DB::table('costs')->where( 'user_id', Auth::user()->id )->get();
        $users = DB::table('users')->get();
        
        foreach ($user_costs as $u_cost) {
            $stat[ $u_cost->date ] = [];
            $stat[ $u_cost->date ]['day']  = '';
            $stat[ $u_cost->date ]['cost'] = 0;
        }
        
        foreach ($user_costs as $u_cost) {
            $stat[ $u_cost->date ]['day'] = $u_cost->date;
            $stat[ $u_cost->date ]['cost'] += $u_cost->value*$u_cost->rate/100;
        }

        foreach ($stat as $s) {
            $total += $s['cost'];
        }

        return view('home/statistics', compact('stat', 'total', 'users') );
    }
    
    public function date_range(Request $request)
    {

        if ($request['from'] === '')
            $request['from'] = '0000-00-00';

        if ($request['to'] === '') {
            $request['to'] = date( "Y-m-d" );
        }

        $users = DB::table('users')->get();

        $stat = [];
        $total = 0;

        $user_costs = DB::table('costs')->where([
            [ 'date', '<=', $request['to']   ],
            [ 'date', '>=', $request['from'] ],
            [ 'user_id', $request['user'] ]
        ])->get();

        foreach ($user_costs as $u_cost) {
            $stat[ $u_cost->date ] = [];
            $stat[ $u_cost->date ]['day']  = '';
            $stat[ $u_cost->date ]['cost'] = 0;
        }
        
        foreach ($user_costs as $u_cost) {
            $stat[ $u_cost->date ]['day'] = $u_cost->date;
            $stat[ $u_cost->date ]['cost'] += $u_cost->value*$u_cost->rate/100;
        }

        foreach ($stat as $s) {
            $total += $s['cost'];
        }

        $prev = $request;

        return view('home/statistics', compact('stat', 'total', 'prev', 'users') );

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
