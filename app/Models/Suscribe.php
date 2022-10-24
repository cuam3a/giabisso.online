<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Suscribe extends Model
{
    protected $table = "suscribe";
    protected $fillable = [
        'email','created_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'date_created'
    ];

    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order)
    {
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('suscribe.email', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count(); // count total rows
        if( $order ){
            $query = $query->orderBy($order['column'], $order['dir']);
        } 

        $query = $query->skip(($start-1)*$perPage)->take($perPage);
        $page = ($start-1)+$perPage/$perPage;

        $query = $query->select('id',
                                'email','created_at')->get();
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

        
    public function getDateCreatedAttribute()
    {    
        return $this->created_at->format('d/m/Y');   
    }

}
