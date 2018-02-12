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
        'info',
        'wallet'
    ];

    protected $encrypted = [
        'code',
        'cw2',
        'wallet'
    ];


    // Relationships BEGIN
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
    // Relationships END


    // Mutators BEGIN
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = encrypt($value);
        $this->attributes['code_hash'] = sha1($value.env('APP_SALT'));
    }

    public function setCw2Attribute($value)
    {
        $this->attributes['cw2'] = encrypt($value);
    }

    public function setWalletAttribute($value)
    {
        $this->attributes['wallet'] = encrypt($value);
    }
    // Mutators END


    // Accessors BEGIN
    public function getCodeAttribute($value)
    {
        return decrypt($value);
    }

    public function getCw2Attribute($value)
    {
        return decrypt($value);
    }

    public function getWalletAttribute($value)
    {
        return decrypt($value);
    }
    // Accessors END
}
