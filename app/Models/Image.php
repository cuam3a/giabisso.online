<?php

namespace App\Models;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Share;

class Image extends Model
{
    protected $table = "images";
    protected $fillable = [
        'product_id','name','path','type','order'
    ];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}