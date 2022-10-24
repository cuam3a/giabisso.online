<?php
namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = "favorite";
    protected $fillable = [
        'customer_id', 'product_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
