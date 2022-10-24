<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = "city";
    protected $fillable = [
        'state_id','code','name', 'abrev', 'active'
    ];
    
    public function state(){
        return $this->belongsTo('App\Models\State', 'state_id');
    }
    public function order(){
        return $this->hasOne('App\Models\Order', 'city');
    }
}
