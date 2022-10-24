<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CategoryCustomerViews extends Model
{
    protected $table = "category_customer_views";
    protected $fillable = ['id','category_id','customer_id','views'];
    protected $dates = ['created_at','updated_at'];

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public static function scopeListado($query, $perPage, $pages, $start, $search="", $sort, $category, $daterange)
    {
        $query = $query->leftJoin('customer', 'category_customer_views.customer_id', '=',  'customer.id');
        $query = $query->leftJoin('category','category_customer_views.category_id' , '=', 'category.id');
        
        if($category != ''){ 
            $query = $query->where('category.id', $category);
        }
        if($daterange != ""){
            $daterange = explode(' - ',$daterange);
            $inicio = Carbon::createFromFormat('d/m/Y', $daterange[0])->startOfDay();
            $fin = Carbon::createFromFormat('d/m/Y', $daterange[1])->endOfDay();
            $query = $query->where('category_customer_views.updated_at','>=',$inicio)
                            ->where('category_customer_views.updated_at','<=',$fin);
        }
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('customer.id', 'like', '%'. $search .'%')
                        ->orWhere('customer.name', 'like', '%'. $search .'%')
                        ->orWhere('customer.lastname', 'like', '%'. $search .'%')
                        ->orWhere('category.name', 'like', '%'. $search .'%');
            });
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
            $page=1;
        }

        $query = $query->select(DB::raw('CONCAT(customer.name," ",customer.lastname) as fullname'),
                                'customer.email',
                                'category.name as category',
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
