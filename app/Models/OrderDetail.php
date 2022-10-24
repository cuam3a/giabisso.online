<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Refunds;

class OrderDetail extends Model
{
    protected $table = "order_detail";
    protected $fillable = [
        'order_id', 'product_id', 'name', 'category', 'brand', 'unit_price', 'amount', 'quantity','created_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'unit_price',
        'amount'
    ];

    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function getAmountAttribute($value) {
        if($value == 0 || $value == null){
            return 0;
        }
        return number_format($value, 2, '.', ',');
    }

    public function getUnitPriceAttribute($value) {
        if($value == 0 || $value == null){
            return 0;
        }
        return number_format($value, 2, '.', ',');
    }

    public function getAvailableProductsForRefund(){
        $refunds = Refunds::where('order_id', $this->order_id)->where('product_id', $this->product_id)->sum('quantity');
        if($refunds){
            return $this->quantity - $refunds;
        }

        else{
            return $this->quantity;
        }
    }
}
