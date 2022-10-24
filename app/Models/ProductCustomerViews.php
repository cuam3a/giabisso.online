<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductCustomerViews extends Model
{
    protected $table = "product_customer_views";
    protected $fillable = ['id','product_id','category_id','customer_id','views'];
    protected $dates = ['created_at','updated_at'];

    public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public static function scopeListado($query, $perPage, $pages, $start, $search="", $sort, $searchByProductNumber = false, $daterange)
    {
        $query = $query->leftJoin('category','product_customer_views.category_id' , '=', 'category.id');
        $query = $query->leftJoin('customer', 'product_customer_views.customer_id', '=',  'customer.id');
        $query = $query->leftJoin('product','product_customer_views.product_id' , '=', 'product.id');
        if($daterange != ""){
            $daterange = explode(' - ',$daterange);
            $inicio = Carbon::createFromFormat('d/m/Y', $daterange[0])->startOfDay();
            $fin = Carbon::createFromFormat('d/m/Y', $daterange[1])->endOfDay();
            $query = $query->where('product_customer_views.updated_at','>=',$inicio)
                            ->where('product_customer_views.updated_at','<=',$fin);
        }
        if( $search != "") 
        {
            if($searchByProductNumber == 'true'){
                $query = $query->where(function($query) use ($search){
                    $query->where('product.product_number', 'like', '%'. $search .'%');
                });
            }
            else{
                $query = $query->where(function($query) use ($search){
                    $query->where('product.name', 'like', '%'. $search .'%')
                            ->orWhere('product.sku', 'like', '%'. $search .'%')
                            ->orWhere('product.description', 'like', '%'. $search .'%');
                });
            }   
        }
        $count = $query->count(); // count total rows
        
        if( $sort )
        {   
            $query = $query->orderBy($sort['column'], $sort['dir']);
        }

        if($start != null){
            $query = $query->skip(($start-1)*$perPage)->take($perPage);
            $page = ($start-1)+$perPage/$perPage;
        }else{
            $page = 1;
        }

        $query = $query->select(DB::raw('CONCAT(customer.name," ",customer.lastname) as fullname'),
                                'customer.email',
                                'product.sku as sku',
                                'product.name as product',
                                'product.product_number',
                                'category.name as category',
                                'product.id',                                
                                'product.image',
                                'product.image_type',
                                'product.image_name',
                                'views')->get();
                      
        $meta['field'] = $sort['column'];
        $meta['start'] = $start;
        $meta['page'] = round($page);
        $meta['pages'] = $pages;
        $meta['perpage'] = $perPage;
        $meta['total'] = $count;

        $results = [
            'meta' => $meta,
            'data' => $query
        ];
        return $results;
    }
}
