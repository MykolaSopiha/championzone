<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $fillable = [
        'date',
        'card_id',
        'user_id',
        'value',
        'currency',
        'rate',
        'info',
        'cost_type_id',
        'bookkeeping_id',
    ];

    public function card()
    {
        return $this->belongsTo('App\Card', 'card_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function bookkeeping()
    {
        return $this->belongsTo('App\Bookkeeping', 'bookkeeping_id', 'id');
    }

    public function costType()
    {
        return $this->belongsTo('App\CostType', 'cost_type_id', 'id');
    }

    public function getValueAttribute($value)
    {
        return number_format($value/100, 2, '.', ' ');
    }

//    public function setValueAttribute($value)
//    {
//        return intval(round($value, 2)*100);
//    }

}
