<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\MessagePresenter;
use DB;

class Message extends Model
{
    public static $from = [
        "ADMIN" => 1,
        "CUSTOMER" => 2
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function present(){
        return new MessagePresenter($this);
    }

    // Retorna el tipo de usuario que escribió el mensaje
    public function getFrom(){
        return $this->from;
    }

    public function scopeGrouped($query){
        $query = $query->leftJoin('orders', 'orders.id', '=', 'messages.order_id')
            ->groupBy('messages.order_id')
            ->orderBy('orders.new_message', 'desc')
            ->select(
                'messages.*', 
                'orders.new_message',
                DB::raw('count(messages.id) as counter')
            );

        return $query;
    }
}
