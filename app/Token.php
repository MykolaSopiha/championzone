<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['date', 'user_id', 'card_id', 'card_code', 'value', 'currency', 'action', 'ask', 'ans', 'status'];
}
