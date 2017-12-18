<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use DB;
use Auth;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        if ( Auth::user()->status === 'admin' ) {
            $data = DB::table('users')->where('id', $id)->limit(1)->get();
            $data = $data[0];
            $statuses = [
                'admin',
                'mediabuyer',
                'accountant',
                'farmer'
            ];
    	   return view( 'home.showaccount', compact('data', 'statuses') );
        } else {
            $data = DB::table('users')->where('id', Auth::user()->id)->limit(1)->get();
            $data = $data[0];
            $statuses = [
                'admin',
                'mediabuyer',
                'accountant',
                'farmer'
            ];
           return view( 'home.showaccount', compact('data', 'statuses') );
        }
    }

    public function store(Request $request)
    {
        if ($request['name'] != "") {
            $user = DB::table('users')->where('id', $request['user_id'] )->get();
            $user = $user[0];
            if ($request->name != $user->name) {
                $this->validate($request, ['name' => 'required|max:255|unique:users']);
                DB::table('users')->where('id', $request['user_id'] )->update(['name' => $request['name']]);
            }
        }

        if ($request['first_name'] != "") {
            $this->validate($request, ['first_name' => 'required|max:255']);
            DB::table('users')->where('id', $request['user_id'] )->update(['first_name' => $request['first_name']]);
        }

        if ($request['last_name'] != "") {
            $this->validate($request, ['last_name' => 'required|max:255']);
            DB::table('users')->where('id', $request['user_id'] )->update(['last_name' => $request['last_name']]);
        }

        if ($request['terra_id'] != "") {
            $user = DB::table('users')->where('id', $request['user_id'] )->get();
            $user = $user[0];
            if ($request->terra_id != $user->terra_id) {
                $this->validate($request, ['terra_id' => 'required|numeric|min:0|unique:users']);
                DB::table('users')->where('id', $request['user_id'] )->update(['terra_id' => $request['terra_id']]);
            }
        }

        if ($request['birthday'] != "0000-00-00") {
            $this->validate($request, ['date' => 'date']);
            DB::table('users')->where('id', $request['user_id'] )->update(['birthday' => date( "Y-m-d", strtotime($request['birthday']))]);
        }

        if ($request['status'] != "") {
            DB::table('users')->where('id', $request['user_id'] )->update(['status' => $request['status']]);
        }


        return redirect('/home/account'.'/'.$request['user_id']);

    }

}