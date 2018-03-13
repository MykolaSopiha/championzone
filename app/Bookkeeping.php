<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Bookkeeping extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'label', 'main', 'description'];

    public function cost() {
        return $this->hasMany('App\Cost', 'bookkeeping_id', 'id');
    }

    public function card() {
        return $this->hasMany('App\Card', 'bookkeeping_id', 'id');
    }

    public function token() {
        return $this->hasMany('App\Token', 'bookkeeping_id', 'id');
    }


    public function makeMain()
    {
        DB::table('bookkeepings')->update(['main' => false]);
        return DB::table('bookkeepings')->where('id', $this->id)->update(['main' => true]);
    }
    public static function getMain()
    {
        return Bookkeeping::where('main', true)->first();
    }
    public function isMain()
    {
        return ($this->main == true);
    }
}
