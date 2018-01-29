<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name',
        'code',
        'code_hash',
        'cw2',
        'date',
        'currency',
        'type',
        'user_id',
        'status',
        'info'
    ];

    protected $encrypted = [
        'code',
        'cw2'
    ];

    public function cost() {
        return $this->hasMany('App\Cost', 'card_id', 'id');
    }

    public function token() {
        return $this->hasMany('App\Token', 'card_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getCodeAttribute($value)
    {
        return decrypt($value);
    }

    public function getCw2Attribute($value)
    {
        return decrypt($value);
    }

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = encrypt($value);
    }

    public function setCw2Attribute($value)
    {
        $this->attributes['cw2'] = encrypt($value);
    }

    public function setCodeHashAttribute($value)
    {
        $this->attributes['code_hash'] = sha1($value.env('APP_SALT'));
    }
}
