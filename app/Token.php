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
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
