<?php

namespace App\Models;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Share;

class Refunds extends Model
{
    protected $table = "refunds";
    protected $fillable = [
        'product_id', 'order_id', 'quantity','reason', 'comment'
    ];

    public static $status = array(
        0 => 'Cancelado',
        1 => 'Pendiente',
        2 => 'En revisión',
        3 => 'Aceptado',
        4 => 'Rechazado'
    );

    public static $status_label = array(
        0 => 'danger',
        1 => 'info',
        2 => 'warning',
        3 => 'success',
        4 => 'danger'
    );

    public function products(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function orders(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function reasons(){
        return $this->belongsTo('App\Models\ReasonsRefund', 'reason_id');
    }

    public function getRefundStatus($id){
        return array(
            "class" => self::$status_label[$id],
            "text" => self::$status[$id]
        );
    }

    function folio(){
        return '#'.str_pad($this->id, 6, "0", STR_PAD_LEFT);
    }


}
