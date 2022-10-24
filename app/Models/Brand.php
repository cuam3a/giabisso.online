<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = "brands";
    protected $fillable = [
        'id', 'name'
    ];

    public function product(){
        return $this->hasMany('App\Models\Product', 'brand_id');
    }

    public function getBrandsInCategory($category_id = 0){
        $where = '';

        /** There is a category in query */
        if($category_id > 0){
            $where = "WHERE p.category_id = $category_id OR p.category_id IN (select id from category where parent_id= $category_id)";
        }

        // echo $where;
        // exit();

        return DB::select("SELECT b.id, b.name as text
        FROM brands b
        JOIN product p on p.brand_id = b.id
        JOIN category c on c.id = p.category_id OR (select parent_id from category where id=$category_id) = p.category_id
        $where
        GROUP BY b.id, b.name
        ORDER BY b.name ASC");
    }

}
