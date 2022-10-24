<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = "permissions";

    protected $fillable = [
        'module_id',
        'admin_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    public function admin_modules(){
        return $this->belongsTo('App\Models\AdminModules', 'module_id');
    }

    public function admin(){
        return $this->belongsTo('App\Models\Admin', 'admin_id');
    }
}
