<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class BugFixController extends Controller
{
    public function index()
    {
        $cards = Card::all();
        foreach ($cards as $card) {
            $wallet = $card->wallet;
            $wallet = encrypt($wallet);
            DB::table('cards')->where('id', $card->id)->update(['wallet' => $wallet]);
        }
        return 'done!';
    }
}
