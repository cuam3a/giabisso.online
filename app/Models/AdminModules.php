<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AdminModules extends Model
{
    protected $table = "admin_modules";

    protected $fillable = [
        'name'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    public function permissions(){
        return $this->hasMany('App\Models\Permission', 'module_id');
    }
}
