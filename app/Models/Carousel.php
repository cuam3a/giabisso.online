<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $table = "carousels";
    protected $fillable = [
        'id', 'name', 'products'
    ];

    public function products(){
        $products = json_decode($this->products);
        if($products){
            $products = Product::whereIn('id',$products)->where('status',Product::$statusWP['Activo'])->get();
        }else{
            $products = [];
        }      
        return $products;
    }
}
