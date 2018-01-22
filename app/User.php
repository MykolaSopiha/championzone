<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'birthday',
        'male',
        'avatar',
        'email',
        'password',
        'terra_id',
        'status',
        'ref_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function card()
    {
        return $this->hasMany('App\Phone', 'user_id');
    }

    public function cost()
    {
        return $this->hasMany('App\Cost',  'user_id');
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-'.$this->id);
    }
}
