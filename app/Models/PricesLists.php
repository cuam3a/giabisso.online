<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;
use App\Models\Suscribe;

class PricesLists extends Authenticatable
{
    protected $guard = "prices_lists";
    protected $table = "prices_lists";
    protected $fillable = [
        'name','created_at'
    ];
    
    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order)
    {
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('prices_lists.id', 'like', '%'. $search .'%')
                        ->orWhere('prices_lists.name', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count(); // count total rows
        
        if( $order ){
            $columnas = ['name' => 'name'];
            if(array_key_exists($order['column'], $columnas)) $order['column'] = $columnas[$order['column']];
            $query = $query->orderBy($order['column'], $order['dir']);
        } 

        $query = $query->skip(($start-1)*$perPage)->take($perPage);
        $page = ($start-1)+$perPage/$perPage;

        $query = $query->select('prices_lists.id',
                                'prices_lists.name')->get();

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

    public function deletePriceList(){
        $name = $this->name;
        $this->delete();
        return $name;
    }

}
