<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['name', 'code', 'cw2', 'date', 'currency', 'user_id', 'status'];
}
