<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Token;
use App\User;
use App\Card;
use Validator;
use DB;
use Auth;
use Mail;
use View;

class TokenController extends Controller
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
        $statuses   = config('assets.token_statuses');

        View::share(compact('statuses', 'currencies'));
    }


    public function index()
    {
        $users_coditions = [];
        $cards_coditions = [];

        if (Auth::user()->status !== 'admin' && Auth::user()->status !== 'accountant') {
            $cards_coditions[] = ['status', 'active'];
            $cards_coditions[] = ['user_id', Auth::user()->id];
            $users_coditions[] = ['id', Auth::user()->id];
        }

        $users = DB::table('users')
            ->select('id', 'name', 'first_name', 'last_name')
            ->where($users_coditions)
            ->get();

        $cards = DB::table('cards')
            ->select('id', 'name', 'code', 'currency')
            ->where($cards_coditions)
            ->get();

        if (Auth::user()->TeamLead()) {
            $myTeam = [];
            $users = DB::table('users')->where('team_id', Auth::user()->team_id)->get();
            foreach ($users as $user) {
                $myTeam[] = $user->id;
            }
            $cards = DB::table('cards')->whereIn('cards.user_id', $myTeam)->get();
        }

        return view('home.tokens.index', compact('cards', 'users', 'statuses', 'currencies'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $token_card = DB::table('cards')->select('code', 'currency')->where('id', $request["card_id"])->first();

        if (!isset($request['ask'])) {
            $request['ask'] = '';
        }

        if (!isset($request['ans'])) {
            $request['ans'] = '';
        }

        if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant') {
            $request['date'] = date("Y-m-d", strtotime($request["date"]));
        } elseif (Auth::user()->TeamLead()) {
            $request['date']    = date("Y-m-d");
        } else {
            $request['date']    = date("Y-m-d");
            $request['user_id'] = intval(Auth::user()->id);
        }

        $rules = [
            'card_id' => 'required|numeric|min:1',
            'action' => 'required',
            'value' => 'required|numeric',
            'rate' => 'required|numeric',
            'date' => 'required|date',
            'user_id' => 'required|numeric|min:1',
        ];

        $data = [
            'value'     => intval(round($request["value"], 2)*100),
            'currency'  => $token_card->currency,
            'card_code' => $token_card->code,
            'date'      => $request['date'],
            'user_id'   => $request['user_id'],
            'card_id'   => $request["card_id"],
            'rate'      => $request['rate'],
            'action'    => $request['action'],
            'ask'       => $request['ask'],
            'ans'       => $request['ans'],
            'status'    => 'active'
        ];

        if ($request['action'] == 'transfer') {

            $rules['card2_id'] = $rules['card_id'];
            $token_card2 = DB::table('cards')->select('code')->where('id', $request["card2_id"])->first();
            $data['card2_id'] = $request["card2_id"];
            $data['card2_code'] = $token_card2->code;

        }

        $this->validate($request, $rules);

        $token = new Token();
        $token->fill($data);
        $token->save();


        //SEND NOTIFICATION MESSAGE begin
        $accountants = User::select('id', 'name', 'first_name', 'last_name', 'email')->where('status', 'accountant')->get();

        foreach ($accountants as $a) {
            if ($a->isOnline()) return redirect('/home/tokens');
        }

        $user = DB::table('users')->select('name', 'first_name', 'last_name')->where('id', $request['user_id'])->first();

        $actions_RU = [
            'deposit'  => 'Пополнить',
            'withdraw' => 'Списать',
            'transfer' => 'Перевести'
        ];

        $data['action'] = $actions_RU[$data['action']];

        foreach ($accountants as $a) {
            Mail::send('emails.token', ['user' => $user, 'data' => $data], function ($message) use ($a) {
                $message->from(env('MAIL_USERNAME'), 'зоначемпиона.com');
                $message->to($a->email);
            });
        }
        //SEND NOTIFICATION MESSAGE end


        return redirect('/home/tokens');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $token = DB::table('tokens')->where('id', $id)->first();

        if (Auth::user()->id == $token->user_id || Auth::user()->status == 'accountant' || Auth::user()->status == 'admin' || Auth::user()->TeamLead()) {

            $token->card_code = decrypt($token->card_code);
            $token->value = $token->value/100; 
            $card  = DB::table('cards')->where('id', $token->card_id)->first();
            $cards = DB::table('cards')->select('id', 'code', 'name', 'currency')->get();
            $statuses = [
                'active',
                'confirmed',
                'trash'
            ];
            return view( 'home.tokens.edit', compact('token', 'card', 'cards', 'statuses') );
        } else {
            return redirect()->route('home.tokens.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $token = DB::table('tokens')->where('id', $id)->limit(1)->get();
        $token = $token[0];

        if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' || ($token->user_id === Auth::user()->id))
        if (isset($_GET['status'])) {

            $status = $_GET['status'];
            
            switch ($status) {
                case 'active':
                    $token = DB::table('tokens')->where('id', $id)->limit(1)->update(['status' => 'active']);
                    break;
                case 'confirmed':
                    $token = DB::table('tokens')->where('id', $id)->limit(1)->update(['status' => 'confirmed']);
                    break;
                case 'trash':
                    $token = DB::table('tokens')->where('id', $id)->limit(1)->update(['status' => 'trash']);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return redirect('/home/tokens');
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
        $request["value"] = intval(round($request["value"], 2)*100);

        $from_card = DB::table('cards')
            ->select('code')
            ->where('id', $request['card_id'])
            ->first();
        $request['card_code'] = $from_card->code;

        $to_card = DB::table('cards')
            ->select('code')
            ->where('id', $request['card2_id'])
            ->first();
        $request['card2_code'] = $to_card->code;

        DB::table('tokens')->where('id', $id)->update(request()->except([
            '_token',
            '_method'
        ]));

        return redirect('/home/tokens');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ( is_array($id) ) {
            foreach ($id as $i) {
                DB::table('tokens')->where([
                    ['id', $i],
                    ['status', 'active']
                ])->delete();
            }
        } else {
            DB::table('tokens')->where([
                ['id', $id],
                ['status', 'active']
            ])->delete();
        }
        return redirect('/home/tokens');
    }
}
