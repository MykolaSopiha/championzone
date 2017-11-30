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
        } else {
            return view('home');
        }
    }

    public function users()
    {
        $users = DB::table('users')->get();
        return view('home.users', compact('users') );
    }

    public function statistics()
    {
        $stat = [];
        $total = 0;

        $user_costs = DB::table('costs')->where( 'user_id', Auth::user()->id )->get();
        
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

        // return dd($stat);
        // return dd($user_costs);
        return view('home/statistics', compact('stat', 'total') );
    }
    
    // public function statistics2(Request $request)
    // {
    //     $stat = [];
    //     $total = 0;

    //     $user_costs = DB::table('costs')->where([
    //         ['user_id', Auth::user()->id],
    //         ['date', '>=', date( "Y/m/d", strtotime($request["from"]) ) ],
    //         ['date', '>=', date( "Y/m/d", strtotime($request["to"]) ) ],
    //     ])->get();

    //             $user_costs = DB::table('costs')->where([
    //         ['user_id', Auth::user()->id],
    //         ['date', '>=', date( "Y/m/d", strtotime($request["from"]) ) ],
    //         ['date', '>=', date( "Y/m/d", strtotime($request["to"]) ) ],
    //     ])->get();
        
    //     foreach ($user_costs as $u_cost) {
    //         $stat[ $u_cost->date ] = [];
    //         $stat[ $u_cost->date ]['day']  = '';
    //         $stat[ $u_cost->date ]['cost'] = 0;
    //     }
        
    //     foreach ($user_costs as $u_cost) {
    //         $stat[ $u_cost->date ]['day'] = $u_cost->date;
    //         $stat[ $u_cost->date ]['cost'] += $u_cost->value*$u_cost->rate/100;
    //     }

    //     foreach ($stat as $s) {
    //         $total += $s['cost'];
    //     }

    //     // return dd($stat);
    //     // return dd($user_costs);
    //     return view('home/statistics', compact('stat', 'total') );
    // }

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
