<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use DB;

class User extends Authenticatable
{
    use SoftDeletes;

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

    protected $dates = ['deleted_at'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param string|array $roles
     * @return bool
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn(‘name’, $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function card()
    {
        return $this->hasMany('App\Phone', 'user_id', 'id');
    }

    public function token()
    {
        return $this->hasMany('App\Token', 'user_id', 'id');
    }

    public function cost()
    {
        return $this->hasMany('App\Cost',  'user_id', 'id');
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-'.$this->id);
    }

    public static function boot()
    {
        parent::boot();

        self::created(function($user){
            DB::table('role_user')->insert([
                'user_id' => $user->id
            ]);
        });
    }
}
