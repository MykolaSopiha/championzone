<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;
use App\Card;
use DB;
use Auth;


class CardController extends Controller
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
        $currencies = ['RUB', 'USD', 'UAH', 'EUR'];
        $types = [
            0 => 'Яндекс.Деньги',
            1 => 'QIWI',
            2 => 'Пластиковая'
        ];
        $cards_coditions = [];

        if (Auth::user()->status == "mediabuyer") {
            $cards_coditions[] = ['user_id', Auth::user()->id];
        }

        $users = DB::select('select id, name, first_name, last_name from users');
        $cards = DB::table('cards')->select('id', 'name', 'code', 'status')->where($cards_coditions)->get();

        return view('/home/cards', compact('users', 'cards', 'types', 'currencies'));
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
        $salt = env('APP_SALT');
        $request['date']      = $request["date"]."/1";
        $request["code_hash"] = sha1($request["code"].$salt);


        // Validation params BEGIN
        $rules = [
            // 'name'      => 'required|max:255|unique:cards',
            'code'      => 'required|numeric|digits:16',
            'code_hash' => 'required|unique:cards',
            'wallet'    => 'numeric',
            'cw2'       => 'required|numeric|digits:3',
            'date'      => 'required|date',
            'user'      => 'required|numeric|min:1',
            'currency'  => 'required|size:3',
            'type'      => 'required|numeric'
        ];

        $QIWI_rules = [
            'code'      => 'required',
            'cw2'       => '',
            'date'      => ''
        ];

        $err_msg = [
            'code_hash.unique' => 'The card code has already been taken.',
            'type.numeric'     => 'The card type is incorrect.'
        ];
        // Validation params END


        // Data to store BEGIN
        $data = [
            'name'      => $request["name"],
            'code'      => encrypt($request["code"]),
            'code_hash' => $request["code_hash"],
            'cw2'       => encrypt($request["cw2"]),
            'date'      => date("Y/m/d", strtotime($request["date"])),
            'currency'  => $request["currency"],
            'user_id'   => $request["user"],
            'status'    => 'active',
            'type'      => intval($request["type"])
        ];

        $QIWI_data = [
            'cw2'  => encrypt('QIWI'),
            'date' => date("Y-m-d")
        ];
        // Data to store END


        if ($request->type === "1") {
            $rules = array_merge($rules, $QIWI_rules);
            $data  = array_merge($data,  $QIWI_data);
        }

        $this->validate($request, $rules, $err_msg);

        $card = new Card();
        $card->fill($data);
        $card->save();

        return redirect('/home/cards');
    }



    public function multiplepage() {
        $users = DB::table('users')->get();
        return view('home.multiple_page', compact('users'));
    }



    public function multipleadd(Request $request) {

        $salt = env('APP_SALT');

        function isDate($value) {
            if (!$value) {
                return false;
            }
            try {
                new \DateTime($value);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        $text  = preg_replace('/[ ]{2,}|[\t]|[\r]/', ' ', trim($request->cards));
        $lines = explode("\n", $text);
        $errors = [];

        foreach ($lines as $line) {

            $word = explode(' ', $line);
            $index = substr($line, 0, 16);
            $errors[$index] = [];
            

            // CHECK CARD CODE begin
            if (is_numeric($word[0])) {
                if (strlen($word[0]) === 16) {
                    $code = $word[0];
                } elseif ( $line[4] == ' ' && $line[9] == ' ' && $line[14] == ' ' ) {
                    $code = "".$word[0]."".$word[1]."".$word[2]."".$word[3];
                    $word = explode( ' ', $code.substr($line, 19) );
                    //return $code.substr($line, 19);
                } else {
                    $errors[$index][] = "Code length isn't 16 digits!";
                }
            } else {
                $errors[$index][] = "Code isn't numeric!";
            }
            // CHECK CARD CODE end
            

            // CHECK CARD DATE begin
            if ($word[1][2] == '/' || $word[1][2] == '\\' || $word[1][2] == '-' || $word[1][2] == '.') {
                $date_check = '01/'.$word[1][0].$word[1][1].'/'.substr($word[1], 3);
                if ( isDate( $date_check ) === true ) {
                    $date = date( "Y-m-d", strtotime( $date_check ) );
                } else {
                    $errors[$index][] = "Date format is incorrect!";
                }
            } else {
                return false;
            }
            // CHECK CARD DATE end


            // CHECK CARD CW2 begin
            if (is_numeric($word[2])) {
                if (strlen($word[2]) === 3) {
                    $cw2 = $word[2];
                } else {
                    $errors[$index][] = "CW2 length isn't 3 digits!";
                }
            } else {
                $errors[$index][] = "CW2 isn't numeric!";
            }
            // CHECK CARD CW2 end

            // return $code.' '.$date.' '.$word[2].' === '.$line;
            // return strpos($line, $word[2]);
            $info = substr($line, strpos($line, $word[2]) );
            $info = substr($info, strlen($word[2])+1);
            $info = trim($info);


            if ( empty($errors[$index]) ) {

                //EVERYTHING IS OK
                $card = new Card();
                $card->fill([
                    'date'      => $date,
                    'code'      => encrypt($code),
                    'code_hash' => sha1("".$code.$salt),
                    'cw2'       => encrypt($cw2),
                    'currency'  => 'RUB',
                    'user_id'   => $request->card_user,
                    'info'      => $info
                ]);
                $card->save();

                $errors[$index] = '';

            } else {
                $errors[$index]['idx'] = $index;                
            }

        }

        $users = DB::table('users')->get();
        return view('home.multiple_page', compact('errors', 'users') );
    }



    public function multiple_action(Request $request)
    {
        if (!is_null($request->card)) {
            switch ($request->card_action) {
                case '1':
                    // Change user
                    if (!is_null($request->card_user)) {
                        DB::table('cards')->whereIn('id', array_keys($request->card))->update(['user_id' => $request->card_user]);
                    }
                    break;

                case '2':
                    // Activate cards
                    DB::table('cards')->whereIn('id', array_keys($request->card))->update(['status' => 'active']);
                    break;

                case '3':
                    // Disactivate cards
                    DB::table('cards')->whereIn('id', array_keys($request->card))->update(['status' => 'disable']);
                    break;

                case '4':
                    // Delete cards
                    $this->destroy( array_keys( $request->card ) );
                    break;

                default:
                    # code...
                    break;
            }
        }
        
        return redirect('/home/cards');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card  = DB::table('cards')->where('id', $id)->first();
        $card->code = decrypt($card->code);
        $card->cw2  = (is_null($card->cw2)) ? null : decrypt($card->cw2);
        $card->date = date("Y/m/d", strtotime($card->date));

        $users = DB::table('users')->get();
        $card_types = [
            ['0', 'Яндекс.Деньги'],
            ['1', 'QIWI'],
            ['2', 'Пластиковая карта']
        ];
        
        return view('home.show.card', compact('card', 'card_types', 'users') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if ($request->action == 'unchain') {

            $tokens = DB::table('tokens')->where([
                ['user_id', Auth::user()->id],
                ['card_id', $id],
                ['status', 'active']
            ])->count();

            if ($tokens == 0) {
                DB::table('cards')->where('id', $id)->update(['user_id'=>null]);
                return redirect('/home/cards');
            } else {
                return view('errors.unchain')->with('id', $id);
            }

        }

        $card = DB::table('cards')->where('id', $id)->first();

        if ($card->status === 'active') {
            DB::update("update cards set status = 'disable' where id = ? limit 1", [$id]);
        } elseif ($card->status === 'disable') {
            DB::update("update cards set status = 'active' where id = ? limit 1", [$id]);
        }

        return redirect('/home/cards');
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

        $salt = env('APP_SALT');

        if ($request['name'] != "") {
            $this->validate($request, ['name' => 'required|max:255']);
            DB::table('cards')->where('id', $id)->update(['name' => $request['name']]);
        }

        if ($request['type'] != "") {
            $this->validate($request, ['type' => 'required|numeric|min:0']);
            DB::table('cards')->where('id', $id)->update(['type' => $request['type']]);
        }

        if ($request['code'] != "") {
            $this->validate($request, ['code' => 'required|max:255']);
            DB::table('cards')->where('id', $id)->update(['code' => encrypt($request['code'])]);
            DB::table('cards')->where('id', $id)->update(['code_hash' => sha1("".$request["code"].$salt)]);
        }

        if ($request['cw2'] != "") {
            $this->validate($request, ['code' => 'required|max:255']);
            DB::table('cards')->where('id', $id)->update(['cw2' => encrypt($request['cw2'])]);
        }

        if (isset($request['date'])) {
            $this->validate($request, ['date' => 'required']);
            DB::table('cards')->where('id', $id )->update(['date' => date( "Y-m-d", strtotime($request['date']))]);
        }

        if ($request['currency'] != "") {
            DB::table('cards')->where('id', $id)->update(['currency' => $request['currency']]);
        }

        if ($request['user'] != "") {
            DB::table('cards')->where('id', $id)->update(['user_id' => $request['user']]);
        }

        if ($request['wallet'] != "") {
            DB::table('cards')->where('id', $id)->update(['wallet' => $request['wallet']]);
        }

        return redirect('/home/cards'.'/'.$request['user_id']);
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
                DB::table('cards')->where('id', $i)->delete();
            }
        } else {
            DB::table('cards')->where('id', $id)->delete();
        }
        return redirect('/home/cards');
    }
}
