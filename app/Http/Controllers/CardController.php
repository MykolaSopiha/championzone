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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {
        if ( Auth::user()->status == 'admin' || Auth::user()->status == 'accountant' ) {
            $cards = DB::table('cards')->where('status', 'active')->orWhere('status', 'disable')->get();
            $users = DB::table('users')->get();
            foreach ($cards as $card) {
                $card->code = decrypt($card->code);
                $code = "".$card->code;
                $card->code = substr($code, 0, 4).'-'.substr($code, 4, 4).'-'.substr($code, 8, 4).'-'.substr($code, 12, 4);
                foreach ($users as $user) {
                    if ( $user->id == $card->user_id ) {
                        $card->user_name = $user->name;
                        break;
                    }
                }
            }
            return view('/home/cards', compact('cards', 'users') );
        } else {
            return view('/home');
        }
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
        $request['date'] = $request["date"]."/1";

        $this->validate($request, [
            'name' => 'max:255|unique:cards',
            'code' => 'required|numeric|digits:16',
            'cw2'  => 'required|numeric|digits:3',
            'date' => 'required|date',
            'user' => 'required|numeric|min:1',
            'currency' => 'required|size:3'
        ]);

        $request["code"] = encrypt( $request["code"] );
        $request["cw2"]  = encrypt( $request["cw2"]  );

        $card = new Card();
        $card->fill([
            'name'      => $request["name"],
            'code'      => $request["code"],
            'cw2'       => $request["cw2"],
            'date'      => date( "Y/m/d", strtotime($request["date"]) ),
            'currency'  => $request["currency"],
            'user_id'   => $request["user"],
            'status'    => 'active'
        ]);
        $card->save();

        return redirect('/home/cards');
    }



    public function multiplepage() {
        return view('home.multiple_page');
    }



    public function multipleadd(Request $request) {

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
                } else {
                    $errors[$index][] = "Code length isn't 16 digits!";
                }
            } else {
                $errors[$index][] = "Code isn't numeric!";
            }
            // CHECK CARD CODE end
            

            // CHECK CARD DATE begin
            if ( isDate('1/'.$word[1]) === true) {
                $date = date( "Y-m-d", strtotime( '1/'.$word[1]) );
            } else {
                $errors[$index][] = "Date format is incorrect!";
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

            $info = substr($line, strpos($line, $word[2]));
            $info = substr($info, strlen($word[2])+1);
            $info = trim($info);


            if ( empty($errors[$index]) ) {

                //EVERYTHING IS OK
                $card = new Card();
                $card->fill([
                    'date'      => $date,
                    'code'      => encrypt($code),
                    'cw2'       => encrypt($cw2),
                    'currency'  => 'RUB',
                    'user_id'   => Auth::user()->id,
                    'info'      => $info
                ]);
                $card->save();

                $errors[$index] = '';

            } else {
                $errors[$index]['idx'] = $index;                
            }

        }

        return view('home.multiple_page', compact('errors') );
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = DB::table('users')->get();
        $card = DB::select('select * from cards where id = ? limit 1', [ $id ] );
        $card = $card[0];
        return view('/home/showcard', compact('card', 'users') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $card = DB::select('select * from cards where id = ? limit 1', [ $id ] );
        $card = $card[0];

        if ($card->status === 'active') {
            DB::update("update cards set status = 'disable' where id = ? limit 1", [ $id ]);
        } elseif ($card->status === 'disable') {
            DB::update("update cards set status = 'active' where id = ? limit 1", [ $id ]);
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
        DB::update("update cards set user_id = ? where id = ?", [ $request->user ,$id ]);
        
        return redirect('/home/cards');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('cards')->where('id', $id)->delete();
        return redirect('/home/cards');
    }
}
