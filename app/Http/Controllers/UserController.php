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
            return view('home.users');
        return redirect('/home');
    }

    public function show($id)
    {
        $user = User::find($id);
        $teams = Team::all();
        $userTeam = Team::find($user->team_id);

        return view('home.show.account', compact('user', 'teams', 'userTeam'));
    }

    public function store(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255|unique:users,id,' . Auth::user()->id,
            'first_name' => 'sometimes|max:255',
            'last_name' => 'sometimes|max:255',
            'terra_id' => 'sometimes|numeric|min:0',
            'birthday' => 'sometimes|date',
            'ref_id' => 'sometimes|numeric|min:0|not_in:' . Auth::user()->id
        ];

        $err_msg = [

        ];

        $this->validate($request, $rules, $err_msg);

        if ($request['terra_id'] == '') {
            $data = $request->except('terra_id');
        } else {
            $data = $request->all();
        }

        User::findOrFail($id)->update($data);

        return redirect('/home/users/' . $request['user_id']);
    }

    public function edit($id, Request $request)
    {
        if (Auth::user()->status != 'admin' || Auth::user()->id != $id)
            return redirect('/home/users/' . $request['user_id']);


        if ($request->has('removeref')) {
            User::findOrFail($id)->update(['ref_id' => 0]);
        }

        return redirect('/home/users/' . $id);
    }

    public function setroleslist()
    {
        $roles = [
            'admin',
            'mediabuyer',
            'accountant',
            'farmer'
        ];

        foreach ($roles as $role) {
            if (DB::table('roles')->where('name', $role)->count() == 0)
                DB::table('roles')->insert(['name' => $role, 'description' => '']);
        }

        return 'hi';
    }

    public function setrole()
    {
        $users = User::all();

        foreach ($users as $user) {
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => Role::select('name', 'id')->where('name', '=', $user->status)->first()->id
            ]);
        }

        return 'hi';
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('home:users.index');
    }

}