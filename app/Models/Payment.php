<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";
    protected $fillable = ["amount, mp_order, mp_payment, mp_method"];

    public function order(){
    	return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function metodo_pago(){
    	if($this->mp_metodo == null){
    		return 'Pendiente';
        }
        
    	return $this->mp_metodo;
    }
}
