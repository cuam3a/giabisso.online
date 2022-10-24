<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = "state";
    protected $fillable = [
        'code','name', 'abrev', 'active'
    ];
    
    public function cities(){
        return $this->hasMany('App\Models\City', 'state_id');
    }
    
    public function order(){
        return $this->hasOne('App\Models\Order', 'state');
    }
}
