<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;
use App\Models\Suscribe;

class PricesListProducts extends Authenticatable
{
    protected $guard = "prices_lists_products";
    protected $table = "prices_lists_products";
    protected $fillable = [
        'price_list_id','product_id','price'
    ];
    
    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order, $id)
    {
        /*$query = DB::table('prices_lists')
            ->where('price_list_id','=',$id)
            ->join('prices_lists_products','prices_lists_products.price_list_id','=','prices_lists.id')
            ->join('product','prices_lists_products.product_id','=','product.id')
            ->get();*/
            $query = $query->leftJoin('product as p', 'p.id', '=', 'prices_lists_products.product_id');
            if( $search != "") 
            {
                $query = $query->where(function($query) use ($search){
                    $query->where('prices_lists_products.id', 'like', '%'. $search .'%')
                            ->orWhere('p.name', 'like', '%'. $search .'%')
                            ->orWhere('prices_lists_products.price', 'like', '%'. $search .'%');
                });
            }
            $count = $query->where('prices_lists_products.price_list_id', '=', $id)->count(); // count total rows
            
            if( $order ){
                $columnas = ['prices_lists_products.id' => 'prices_lists_products.id'];
                if(array_key_exists($order['column'], $columnas)) $order['column'] = $columnas[$order['column']];
                $query = $query->orderBy($order['column'], $order['dir']);
            } 
    
            $query = $query->skip(($start-1)*$perPage)->take($perPage);
            $page = ($start-1)+$perPage/$perPage;
    
            $query = $query->select('prices_lists_products.id',
                                    'prices_lists_products.price_list_id',
                                    'product.name',
                                    'product.regular_price',
                                    'prices_lists_products.price')
                                    ->join('product','prices_lists_products.product_id','=','product.id')
                                    ->where('prices_lists_products.price_list_id', '=', $id)
                                    ->get();
    
            $meta['field'] = $order['column'];
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

    public function deletePricesListProducts(){
        //$name = $this->name;
        $this->delete();
        //return $name;
    }

}
