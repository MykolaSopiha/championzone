<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Datatables;
use Validator;
use View;
use Auth;
use DB;


use App\Card;
use App\Token;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $roles = config('roles');
        View::share(compact('roles'));
    }

    public function index()
    {
        if (Auth::user()->status === 'admin')
            return view('home.users');
        return redirect('/home');
    }

    public function show($id)
    {
        $ref_link = url('register/?ref=').$id;
        $data = User::find($id);
        $patron = [];

        if ($data['ref_id'] != 0) {
            $patron = User::where('id', $data['ref_id'])->select('name', 'first_name', 'last_name')->first();
            if ($patron->first_name == "" || $patron->last_name == "") {
                $patron = $patron->name;
            } else {
                $patron = $patron->first_name." ".$patron->last_name;
            }
        }

        $refs = User::select('id', 'name', 'first_name', 'last_name')->where('ref_id', '=', $id)->get();

        return view( 'home.show.account', compact('data', 'ref_link', 'patron', 'refs') );
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token', 'user_id', 'ref_link']);

        if ($request['ref_link'] != "") {
            $ref_link = parse_url($request['ref_link']);
            if (isset($ref_link['query'])) {
                parse_str($ref_link['query'], $query);
                $data['ref_id'] = intval($query['ref']);
                $request['ref_id'] = intval($query['ref']);
            }
        } else {
            $data['ref_id'] = 0;
        }

        $data['terra_id'] = (empty($data['terra_id'])) ? null : $data['terra_id'];

        $rules = [
            'name' => 'required|max:255|unique:users,id,'.Auth::user()->id,
            'first_name' => 'sometimes|max:255',
            'last_name' => 'sometimes|max:255',
            'terra_id' => 'sometimes|numeric|min:0|unique:users,id,'.Auth::user()->id,
            'birthday' => 'sometimes|date',
            'ref_id' => 'sometimes|numeric|min:0|not_in:'.Auth::user()->id
        ];

        $err_msg = [
            'ref_id.not_in' => 'It is your ref link!'
        ];

        $this->validate($request, $rules, $err_msg);

        User::findOrFail($request['user_id'])->update($data);

        return redirect('/home/users/'.$request['user_id']);
    }

    public function edit ($id, Request $request)
    {
        if (Auth::user()->status != 'admin' || Auth::user()->id != $id)
            return redirect('/home/users/'.$request['user_id']);


        if ($request->has('removeref')) {
            User::findOrFail($id)->update(['ref_id' => 0]);
        }

        return redirect('/home/users/'.$id);
    }

}