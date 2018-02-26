<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookkeeping extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function cost() {
        return $this->hasMany('App\Cost', 'bookkeeping_id', 'id');
    }

    public function card() {
        return $this->hasMany('App\Card', 'bookkeeping_id', 'id');
    }

    public function token() {
        return $this->hasMany('App\Token', 'bookkeeping_id', 'id');
    }

}
