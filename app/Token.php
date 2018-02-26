<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'date',
        'user_id',
        'card_id',
        'card_code',
        'card2_id',
        'card2_code',
        'value',
        'currency',
        'rate',
        'action',
        'ask',
        'ans',
        'status',
        'bookkeeping_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }

    public function card()
    {
        return $this->belongsTo('App\Card', 'id', 'card_id');
    }

    public function bookkeeping()
    {
        return $this->belongsTo('App\Bookkeeping', 'bookkeeping_id', 'id');
    }

}
