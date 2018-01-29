<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function cost()
    {
        return $this->hasMany('App\Cost', 'cost_type_id', 'id');
    }
}
