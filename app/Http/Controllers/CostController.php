<?php

namespace App\Http\Controllers;

use App\Card;
use App\CostType;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Cost;
use Auth;
use DB;
use View;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $currencies = config('assets.currencies');
        View::share(compact('currencies'));
    }

    public function index()
    {
        $users = User::all();
        $costs = Cost::all();
        $costtypes = CostType::all();
        return view('home/costs', compact('costs', 'costtypes', 'users'));
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

    public function costTypes()
    {
        if (Auth::user()->status != 'admin' && Auth::user()->status != 'accountant')
            return redirect('home/costs');

        $cost_types = CostType::all();

        return view('home.costtypes', compact('cost_types'));
    }

    public function saveType(Request $request)
    {
        if (Auth::user()->status != 'admin' && Auth::user()->status != 'accountant')
            return redirect('home/costs');

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $data = $request->except('_token');

        CostType::create($data);

        return redirect('home/costtypes');
    }

    public function deleteType($id)
    {
        if (Auth::user()->status != 'admin')
            return redirect('home/costs');

        CostType::findOrFail($id)->delete();
        return redirect('home/costtypes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        $this->validate($request, [
            'date'     => 'required|date',
            'card_id'  => 'sometimes|numeric|min:1',
            'user_id'  => 'sometimes|numeric|min:1',
            'value'    => 'required|numeric',
            'rate'     => 'required|numeric'
        ]);

        $data['card_id'] = (is_null($request["card_id"])) ? 0 : $request["card_id"];
        $data['user_id'] = (is_null($request["user_id"])) ? Auth::user()->id : $request["user_id"];
        $data['value'] = intval(round($data['value'], 2)*100);

        Cost::create($data);

        return redirect()->route('home:home.costs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cost::destroy($id);
        return redirect('home/costs');
    }
}
