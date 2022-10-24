<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductViews extends Model
{
    protected $table = "product_views";
    protected $fillable = ['id','product_id','category_id','views'];
    protected $dates = ['created_at','updated_at'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
