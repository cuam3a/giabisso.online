<?php

namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = "brands";

    protected $fillable = [
        'name',
    ];

    public function getBrandsByCategory($category_id = 0){
        $where = '';
        
        /** There is a category in query */
        if($category_id != 0){
            $where = "WHERE c.id = $category_id ";
        }

        return DB::select("SELECT b.id, b.name
        FROM brands b
        JOIN product p on p.brand_id = b.id
        JOIN category c on c.id = p.category_id
        $where
        GROUP BY b.id, b.name
        ORDER BY b.name ASC");
    }


}

