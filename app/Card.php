<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['name', 'code', 'code_hash', 'cw2', 'date', 'currency', 'user_id', 'status', 'info'];
    protected $encrypted = ['code', 'cw2'];
}
