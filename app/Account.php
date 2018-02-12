<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id', 'info', 'price', 'rate', 'currency'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
