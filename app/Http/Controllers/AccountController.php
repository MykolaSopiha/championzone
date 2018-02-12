<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Account;
use DB;
use Auth;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conditions = [];

        if (Auth::user()->status != 'admin') {
            $conditions[] = ['user_id', Auth::user()->id];
        }

        $users = User::all();
        $accounts = Account::where($conditions)->get();

        return view('home.accounts.index', compact('users', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *users.id
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
            'info'      => 'required',
            'user'      => 'required|numeric|min:1',
            'value'     => 'required|numeric|min:0',
            'currency'  => 'required',
            'rate'      => 'required|numeric|min:0',
        ], [
           'user.numeric' => 'The user id is incorrect.' 
        ]);

        $account = new Account();
        $account->fill([
            'info'      => $request['info'],
            'user_id'   => intval($request['user']),
            'price'     => intval(round($request["value"], 2)*100),
            'rate'      => $request['rate'],
            'currency'  => $request['currency'],
        ]);
        $account->save();

        return back()->with(['Account saved!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = DB::table('accounts')->where('id', $id)->first();
        $users   = DB::select('select id, name from users');

        return view('home.accounts.edit', compact('account', 'users'));
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

        $request["price"] = intval(round($request["price"], 2)*100);
        $request["rate"]  = floatval($request["rate"]);
        
        DB::table('accounts')->where('id', $id)
            ->update(
                request()->except([
                    '_token',
                    '_method'
                ])
            );

        return redirect('/home/accounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::delete('delete from accounts where id = ? limit 1', [$id]);

        return redirect('/home/accounts');
    }
}
