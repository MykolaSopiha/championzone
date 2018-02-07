<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Team extends Model
{
    protected $fillable = ['name', 'team_lead_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'team_id', 'id');
    }

    public function leader()
    {
        return $this->hasOne('App\User', 'id', 'team_lead_id');
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($team) {
            DB::table('users')->where('id', $team->team_lead_id)->update([
                'team_id' => $team->id
            ]);
        });
    }
}
