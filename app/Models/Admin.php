<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use DB;
use Carbon\Carbon;
use App\Models\Permission;

class Admin extends Authenticatable
{
    protected $guard = "admin";
    protected $table = "admin";
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password',
        'reset_token',
        'remember_token'
    ];
    
    protected $appends = [
        'date_created'
    ];

    public function getDateCreatedAttribute()
    {    
        if($this->created_at == null) return '';
        
        return $this->created_at->format('d/m/Y');   
    }
    
    public function permissions(){
        return $this->hasMany('App\Models\Permission', 'admin_id');
    }

    public function permissionList(){
        return Permission::where('admin_id',$this->id)->pluck('module_id')->toArray();
    }

    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order)
    {
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('admin.id', 'like', '%'. $search .'%')
                        ->orWhere('admin.name', 'like', '%'. $search .'%')
                        ->orWhere('admin.email', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count(); // count total rows
        
        if( $order ){
            $query = $query->orderBy($order['column'], $order['dir']);
        } 

        $query = $query->skip(($start-1)*$perPage)->take($perPage);
        $page = ($start-1)+$perPage/$perPage;

        $query = $query->select('admin.id',
                                'admin.name',
                                'admin.email',
                                'admin.created_at')->get();

        $meta['field'] = $order['column'];
        $meta['start'] = $start;
        $meta['page'] = round($page);
        $meta['pages'] = $pages;
        $meta['perpage'] = $perPage;
        $meta['total'] = $count;

        $results = [
            'meta' => $meta,
            'data' => $query
        ];
        return $results;
    }

}
