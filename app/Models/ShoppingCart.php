<?php

namespace App\Models;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Share;
use App\Presenters\ShoppingCartPresenter;

class ShoppingCart extends Model
{
    protected $table = "shoppingcart";
    protected $primaryKey = 'identifier';
    protected $fillable = [
        'identifier','instance','content','total','created_at',
    ];
    protected $dates = [
        'created_at', 'updated_at'
    ];
    protected $appends = [
        'products',
        'date_updated',
        'total'
    ];
    public static function abandonedCarts(){
        return DB::table('shoppingcart')->orWhere(function($query){    
                    $query->where(DB::raw('DATE(updated_at)'), Carbon::now()->subDays(env('ABANDONED_CARTS_DAYS',1))->format('Y-m-d'));                
                    $query->orWhere(DB::raw('DATE(updated_at)'),Carbon::now()->subDays(7)->format('Y-m-d'));
                    $query->orWhere(DB::raw('DATE(updated_at)'), Carbon::now()->subDays(14)->format('Y-m-d'));
                })->get();
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
    public function date_created()
    {    
        return $this->created_at->format('d/m/Y g:i a');   
    }
    public function getContent(){
        return unserialize($this->content);
    }
    public function present(){
        return new ShoppingCartPresenter($this);
    }

    public function getProductsAttribute() {
        $products = unserialize($this->content);
        $products = $products->map(function ($item, $key) {
           return $item->name;
       });
        return $products;
   }

    public function getDateUpdatedAttribute() { 
        $updated = new Carbon($this->updated_at);//si en pasos 
        $updated = $updated->format('d/m/Y');//porq extrañamente no funciona
        return $updated;
    }

    public function getTotalAttribute()
    {
        return dinero($this->attributes['total']);
    }

    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order)
    {
        $query = $query->leftJoin('customer', 'customer.id', '=', 'shoppingcart.customer_id');
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('customer.name', 'like', '%'. $search .'%')
                        ->orWhere('customer.lastname', 'like', '%'. $search .'%')
                        ->orWhere('customer.email', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count(); // count total rows
        
        if( $order ){
            $columnas = ['fullname' => 'name', 'date_updated' => 'updated_at'];
            if(array_key_exists($order['column'], $columnas)) $order['column'] = $columnas[$order['column']];
            $query = $query->orderBy($order['column'], $order['dir']);
        } 

        $query = $query->skip(($start-1)*$perPage)->take($perPage);
        $page = ($start-1)+$perPage/$perPage;

        $query = $query->select(DB::raw('CONCAT(customer.name," ",customer.lastname) as fullname'),
                                'customer.email',
                                'shoppingcart.content',
                                'shoppingcart.total',
                                'shoppingcart.updated_at')->get();

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
}
    