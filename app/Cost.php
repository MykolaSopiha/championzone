<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $fillable = ['card_id', 'date', 'value', 'rate', 'user_id', 'info'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getValueAttribute($value)
    {
        return $value/100;
    }
}
