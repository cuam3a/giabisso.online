<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Refunds;

class OrderProgramming extends Model
{
    protected $table = "order_programming";
    protected $fillable = [
        'id', 'customer_id', 'name', 'status', 'created_at','subtotal','shipping', 'total'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'payment_date'
    ];
    
    public static $status = [
        'Pendiente',
        'Procesando',
        'Cancelado',
        'Entregado'
    ];

    public static $status_colors = [
        'warning',//'Pendiente' => 
        'success',//'Procesando' =>
        'danger',//'Cancelado' => 
        'info' // Entregado
    ];
    
    public static $payment = [
        'Pendiente',
        'Pagado',
    ];

    public static $payment_text = [//Texto que se mostrara a cliente
        'pendiente' => 0,//valor default en bd
        'pagado' => 1
    ];

    public static $payment_colors = [
        'warning',//'Pendiente' => 
        'primary',//'Pagado' => 
    ];

    public static $delivery_service = [
        0 => 'Sin asignar',
        1 => 'DHL',
        2 => 'Paquetexpress',
        //2 => 'Estafeta',
        //3 => 'Paquete express',
    ];

    protected $appends = [
        'folio_text',
        'date_created',
        'status_badge',
        'status_text',
        'payment_badge',
        'payment_text',
        'invoiceRequired',
        'invoice_badge',
        'total_money',
        'delivery_text'
    ];
    
    public static $minPurchase = 2000;//Minimo de compra
    public static $shippingCost = 245.50;//Costo de envio al NO cumplirse minimo de compra

    /** Relaciones */
    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function messages(){
        return $this->hasMany(Message::class, 'order_id');
    }

    public function payment(){
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function City(){
        return $this->belongsTo('App\Models\City', 'city');
    }

    public function State(){
        return $this->belongsTo('App\Models\State', 'state');
    }

    public function f_City(){
        return $this->belongsTo('App\Models\City', 'f_city');
    }

    public function f_State(){
        return $this->belongsTo('App\Models\State', 'f_state');
    }

    public function order_details(){
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }

    public function order_shipment_type(){
        return $this->belongsTo('App\Models\ShipmentTypes', 'shipment_id');
    }

    /** Formatos */
    public function folio(){
        return '#'.str_pad($this->id, 6, "0", STR_PAD_LEFT);
    }
    public function fullname(){
        return $this->name.' '.$this->lastname;
    }

    public function address(){
        return $this->address.' '.$this->street_number.' '.$this->suit_number.' '.$this->between_streets;
    }

    public function fullAddress(){
        return $this->address.' '.$this->street_number.' '.$this->suit_number.' '.$this->between_streets.'<br>'.$this->City->name.', '.$this->State->name.'<br> C.P. '.$this->zipcode;
    }

    public function fullAddressInvoice(){
        if($this->invoice_require == null) return '';
        return $this->f_address.'<br>'.$this->f_City->name.','.$this->f_State->name;
    }

    public function date_payment(){
        if($this->payment_date == null) return 'Pago pendiente';     
        return $this->payment_date->format('d/m/Y g:i a');   
    }
    
    public function date_created()
    {    
        return $this->created_at->format('d/m/Y g:i a');   
    }

    public function date()
    {    
        return $this->created_at->format('d/m/Y');   
    }

    public function date_created_day()
    {    
        return $this->created_at->format('d/m/Y');   
    }

    public function date_created_time()
    {    
        return $this->created_at->format('g:i a');   
    }

    public function getTotalMoneyAttribute()
    {
        return "";//dinero($this->attributes['total']);
    }

    public function datetext_created()
    {   
        setlocale(LC_TIME, 'Spanish');
        return $this->payment_date->formatLocalized('%d  de %B de %Y');  
    }
    
    public function getFolioTextAttribute(){     
        return str_pad($this->id, 6, "0", STR_PAD_LEFT);  
    }

    public function getDateCreatedAttribute(){     
        return $this->created_at->format('d/m/Y');   
    }

    public function getInvoiceRequiredAttribute(){
        return $this->invoice_require ? 'Si' : 'No';
    }

    public function getInvoiceBadgeAttribute(){
        if($this->invoice_require){
			return '<i class="la la-check-circle" style="color:#34bfa3"></i>';
        }
        return '<i class="la la-times-circle" style="color:#f4516c"></i>';
    }

    public function getStatusTextAttribute()
    {   
        return self::$status[$this->attributes['status']];
    }
    
    public function getStatusBadgeAttribute()
    {   
        return '<span class="badge badge-'.self::$status_colors[$this->attributes['status']].' m-badge m-badge--'.self::$status_colors[$this->attributes['status']].' m-badge--wide">'.self::$status[$this->attributes['status']].'</span>';
    }

    public function getPaymentTextAttribute()
    {
        return self::$status[$this->attributes['status']];
    }

    public function getPaymentBadgeAttribute()
    {
        return '<span></span>';
    }
    
    public function getPaymentStatusHtml(){
        if($this->payment_status == 1){
            return ' <div class="col-md-12 text-center"><h3><span class="badge badge-success"> Gracias por tu pago</span>  </h3><hr></div>';
        }
            
    }

    public function present(){
        return new OrderPresenter($this);
    }

    public function getDeliveryTextAttribute(){
        if($this->delivery_service === null) return 'N\A';
        return self::$delivery_service[$this->delivery_service].' - '.$this->tracking_number;
    }
    public function getFillables() {
        return $this->fillable;
    }

    public function count($value){
        $count = OrderProgramming::where('customer_id','=',$value)->count();
        if($count == 0 || $count == null){
            return 0;
        }
        return $count;
    }

    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order, $payment, $status)
    {
        
        if($status != ''){
            $query = $query->where('order_programming.status', $status);
        }
        
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('order_programming.id', 'like', '%'. $search .'%')
                        ->orWhere('order_programming.name', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count(); // count total rows
        
        if( $order )
        {   
            $columnas = ['folio_text' => 'id','date_created' => 'created_at','payment_badge' =>'status'];
            if(array_key_exists($order['column'], $columnas)) $order['column'] = $columnas[$order['column']];
            $query = $query->orderBy($order['column'], $order['dir']);
        }

        $query = $query->skip(($start-1)*$perPage)->take($perPage);
        $page = ($start-1)+$perPage/$perPage;

        $query = $query->select('order_programming.id',
                                'order_programming.name',
                                'order_programming.status',
                                //DB::raw('DATE_FORMAT(order_programming.created_at, "%d-%m-%Y") as created_at'),
                                'order_programming.created_at')->get();
                    
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

    public function countDetails($value){
        $count = OrderProgrammingDetails::where('order_programming_id','=',$value)->count();
        if($count == 0 || $count == null){
            return 0;
        }
        return $count;
    }

    public function totalOrder($value){
        $total = 0;
        $orderDetails = OrderProgrammingDetails::where('order_programming_id','=',$value)->get();
        foreach($orderDetails as $item){
            $total += ($item->quantity * $item->unit_price);
        }
        return $total;
    }

}