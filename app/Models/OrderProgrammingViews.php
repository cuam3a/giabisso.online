<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Refunds;

class OrderProgrammingViews extends Model
{
    protected $table = "order_programming_views";
    protected $fillable = [
        'id', 'customer_id', 'product_id', 'sku', 'name', 'category', 'unit_price', 'quantity', 'date_send', 'status', 'created_at'
    ];

    public function count($value){
        $count = OrderProgrammingViews::where('customer_id','=',$value)
                        ->Where('status','=',0)->count();
        if($count == 0 || $count == null){
            return 0;
        }
        return $count;
    }
}