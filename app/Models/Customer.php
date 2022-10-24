<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;
use App\Models\Suscribe;

class Customer extends Authenticatable
{
    protected $guard = "customer";
    protected $table = "customer";
    protected $fillable = [
        'name', 'lastname', 'email', 'password','price_list_id','phone','cell_phone','f_name','f_rfc','f_address','f_zipcode','f_state','f_city','created_at','credit','credit_days','credit_status'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
    

    protected $hidden = [
        'password',
        'reset_token',
        'remember_token'
    ];

    protected $appends = [
        'date_created'
    ];

    public static $status = array(
        0 => 'Cancelado',
        1 => 'Pendiente',
        2 => 'En revisión',
        3 => 'Aceptado',
        4 => 'Rechazado'
    );

    public static $status_label = array(
        0 => 'danger',
        1 => 'info',
        2 => 'default',
        3 => 'success',
        4 => 'warning'
    );

    public static $creditStatus = array(
        0 => 'INACTIVO',
        1 => 'Activo'
    );

    public function shoppingCart(){
        return $this->hasOne('App\Models\ShoppingCart', 'customer_id');
    } 
    public function address_book(){
        return $this->hasMany('App\Models\AddressBook', 'customer_id');
    }

    public function favorites(){
        return $this->hasMany('App\Models\Favorite', 'customer_id');
    }
    
    public function orders(){
        return $this->hasMany('App\Models\Order', 'customer_id');
    }

    public function suscribed(){
        return Suscribe::where('email',$this->email)->get()->count()>0;
    }
    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order)
    {
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('customer.id', 'like', '%'. $search .'%')
                        ->orWhere('customer.name', 'like', '%'. $search .'%')
                        ->orWhere('customer.lastname', 'like', '%'. $search .'%')
                        ->orWhere('customer.email', 'like', '%'. $search .'%')
                        ->orWhere('customer.phone', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count(); // count total rows
        
        if( $order ){
            $columnas = ['fullname' => 'name'];
            if(array_key_exists($order['column'], $columnas)) $order['column'] = $columnas[$order['column']];
            $query = $query->orderBy($order['column'], $order['dir']);
        } 

        $query = $query->skip(($start-1)*$perPage)->take($perPage);
        $page = ($start-1)+$perPage/$perPage;

        $query = $query->select('customer.id',
                                 DB::raw('CONCAT(customer.name," ",customer.lastname) as fullname'),
                                'customer.lastname',
                                'customer.email',
                                'customer.cell_phone',
                                'customer.phone',
                                'customer.created_at')->get();

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

    public function fullname(){
        return $this->name.' '.$this->lastname;
    }
    
    public function getDateCreatedAttribute()
    {    
        return $this->created_at->format('d/m/Y');   
    }

    public function refunds(){
        return DB::table('refunds')
        ->join('orders', 'orders.id', '=', 'refunds.order_id')
        ->join('product', 'product.id', '=', 'refunds.product_id')
        ->join('reasons_refund', 'reasons_refund.id', '=', 'refunds.reason_id')
        ->where('orders.customer_id', $this->id)
        ->select('orders.id as order_id', 'orders.email', 'refunds.created_at as date', 'refunds.comment', 'refunds.comment_admin', 'refunds.quantity', 'product.name as product', 'product.id as product_id', 'product.image as product_image', 'product.slug', 'reasons_refund.description as reason', 'refunds.id', 'refunds.status')
        ->orderBy('refunds.created_at', 'desc')
        ->get();
    }

    public function getRefundStatus($id){
        return array(
            "class" => self::$status_label[$id],
            "text" => self::$status[$id]
        );
    }

    public function favoriteCategories(){
        $maxCategory = $this->hasMany('App\Models\CategoryCustomerViews', 'customer_id')->orderBy('views','DESC')->first();
        if($maxCategory){
            return $maxCategory->category->products->take(12);
        }
        else{
            return [];
        }
    }

    //GCUAMEA
    public function getCity(){
        $city = DB::table('city')->where('id','=',$this->f_city)->first();
        return $city->name;
    }

    public function getState(){
        $city = DB::table('state')->where('id','=',$this->f_state)->first();
        return $city->name;
    }

    public function getSaldo(){
        $orders = DB::table('orders')->where('customer_id','=',$this->id)->get();
        $sum = $orders->where('payment_status','=',0)->sum('total');
        return $sum;
    }

    public function getAvailable(){
        $orders = DB::table('orders')->where('customer_id','=',$this->id)->get();
        $sum = $orders->where('payment_status','=',0)->sum('total');
        return ($this->credit - $sum);
    }
    
    public function getCreditstatus(){
        return self::$creditStatus[$this->credit_status];
    }

    public function ordersProgramming(){
        return $this->hasMany('App\Models\OrderProgramming', 'customer_id');
    }
}
