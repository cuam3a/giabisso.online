<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Refunds;

class OrderProgrammingDetails extends Model
{
    protected $table = "order_programming_details";
    protected $fillable = [
        'id', 'order_programming_id', 'product_id', 'sku', 'name', 'category', 'unit_price', 'quantity', 'date_send', 'status', 'created_at'
    ];

    public function count($value){
        $count = OrderProgrammingDetails::where('order_programming_id','=',$value)->count();
        if($count == 0 || $count == null){
            return 0;
        }
        return $count;
    }

    
}