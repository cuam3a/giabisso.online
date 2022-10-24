<?php
namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "category";
    protected $fillable = [
        'id','name', 'icon', 'parent_id','order','slug'
    ];

    public static function parents(){
        return Category::where('parent_id',null)->pluck('name','id');
    }

    public function parent_category(){
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function subcategory(){
        return $this->hasMany('App\Models\Category', 'parent_id')->orderBy('name','asc');
    }
    public static function categoriesAll(){
         return collect(Category::all())->mapWithKeys(function ($item) {
            return [$item['id'] => ['name' => $item['name'],
                                    'parent_id' => $item['parent_id'],
                                    'icon' => $item['icon']
                                   ]
                    ];
        });
    }
    public function products(){
        return $this->hasMany('App\Models\Product', 'category_id');
    }

    public function productsActivesAndStock(){
        return $this->hasMany('App\Models\Product', 'category_id')->where('product.status',1);
    }

    public function categoriesWithProductsAndStock($slug = '', $limit = ''){
        $extra_query = '';
        $limit_q = '';
        if($slug != ''){
            $extra_query = " AND c.name = '$slug' ";
        }

        if($limit != ''){
            $limit_q = " LIMIT $limit";
        }

        return DB::select("SELECT c.name, c.id, c.slug, c.icon
                            FROM category c 
                            JOIN category cs on cs.parent_id = c.id
                            JOIN product p on p.category_id = c.id or p.category_id = cs.id
                            WHERE p.status = 1 /*and p.stock > 0*/
                            $extra_query
                            GROUP BY c.id,c.name,c.slug,c.order,c.icon
                            ORDER BY c.order ASC".$limit_q);
    }

    public function subCategoriesWithProductsAndStock($id){
        return DB::select("SELECT c.id as parent_id, cs.id, cs.slug, cs.name, cs.icon
                        FROM category c 
                        JOIN category cs on cs.parent_id = c.id
                        JOIN product p on p.category_id = cs.id
                        WHERE p.status = 1 /*and p.stock > 0*/ AND c.id = $id
                        GROUP BY cs.id, cs.slug, cs.name, c.id, cs.icon
                        ORDER BY cs.name ASC");
    }

    public function subCategoriesWithProductsAndStock2($id){
        return DB::select("SELECT c.id as parent_id, cs.id, cs.slug, cs.name, cs.icon
                        FROM category c 
                        JOIN category cs on cs.parent_id = c.id
                        JOIN product p on p.category_id = cs.id
                        WHERE p.status = 1 /*and p.stock > 0*/ AND c.name = '$id'
                        GROUP BY cs.id, cs.slug, cs.name, c.id, cs.icon
                        ORDER BY cs.name ASC");
    }
}
