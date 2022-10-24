<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;
use App\Models\Suscribe;

class RequestProduct extends Authenticatable
{
    protected $guard = "request_product";
    protected $table = "request_product";
    protected $fillable = [
        'id','name','description','id_customer','name_customer','created_at','updated_at'
    ];

    public function deleteRequestProduct(){
        //$name = $this->name;
        $this->delete();
        //return $name;
    }

}
