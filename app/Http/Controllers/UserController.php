<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Datatables;
use Validator;
use View;
use Auth;
use DB;
use App\Role;

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
            return view('home.users.index');
        return redirect('/home');
    }

    public function show($id)
    {
        $user = User::find($id);
        $teams = Team::all();
        $userTeam = Team::find($user->team_id);

        return view('home.users.edit', compact('user', 'teams', 'userTeam'));
    }

    public function store(Request $request, $id)
    {
        $request['terra_id'] = ($request['terra_id'] == "") ? null : intval($request['terra_id']);

        $rules = [
            'name' => 'max:255|unique:users,name,'.$request['name'],
            'first_name' => 'max:255',
            'last_name' => 'max:255',
            'terra_id' => 'numeric|min:0|unique:users,terra_id,'.$request['terra_id'],
            'birthday' => 'date',
        ];

        $this->validate($request, $rules);
        User::findOrFail($id)->update($request->all());
        return back()->with('Data stored!');
    }

    public function edit($id, Request $request)
    {
        $user = User::findOrFail($id);
        $teams = Team::all();
        return view('home.users.edit', compact('user', 'teams'));
    }

    public function delete($id)
    {
        User::findOrFail($id)->destroy($id);
        return redirect()->route('home.users.index')->with(['success' => 'User deleted!']);
    }
}