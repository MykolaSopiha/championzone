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
        $rules = [
            'name' => 'required|max:255|unique:users,id,' . Auth::user()->id,
            'first_name' => 'sometimes|max:255',
            'last_name' => 'sometimes|max:255',
            'terra_id' => 'sometimes|numeric|min:0',
            'birthday' => 'sometimes|date',
        ];

        $request['bk_select'] = (isset($request['bk_select'])) ? true : false;

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
