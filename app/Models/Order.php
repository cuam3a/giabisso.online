<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Presenters\OrderPresenter;
use App\Models\OrderDetail;
use App\Models\Config;


class Order extends Model
{
    protected $table = "orders";
    protected $fillable = [
        'customer_id', 'name', 'lastname', 'email', 'phone','cell_phone','street_number','suit_number','neighborhood',
        'city','state', 'instruction_place','address','between_streets','zipcode','f_name', 'f_rfc', 'f_email',
        'f_address', 'f_phone', 'f_city', 'f_state', 'invoice_require', 'delivery_service','tracking_number',
        'comments', 'status','payment_status','subtotal','shipping', 'total', 'created_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'payment_date'
    ];
    
    public static $status = [
        'Pendiente',
        'Procesando',
        'Enviado',
        'Cancelado',
        'Entregado'
    ];

    public static $status_colors = [
        'warning',//'Pendiente' => 
        'success',//'Procesando' => 
        'primary',//'Enviado' => 
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
        return dinero($this->attributes['total']);
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
        return self::$payment[$this->attributes['payment_status']];
    }

    public function getPaymentBadgeAttribute()
    {
        return '<span class="badge badge-'.self::$payment_colors[$this->attributes['payment_status']].' m-badge m-badge--'.self::$payment_colors[$this->attributes['payment_status']].' m-badge--wide">'.self::$payment[$this->attributes['payment_status']].'</span>';
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
    
    public static function scopeListado($query, $perPage, $pages, $start, $search="", $order, $payment, $status)
    {
        if($payment != ''){           
            $query = $query->where('orders.payment_status', $payment);
        }
        if($status != ''){
            $query = $query->where('orders.status', $status);
        }
        
        if( $search != "") 
        {
            $query = $query->where(function($query) use ($search){
                $query->where('orders.id', 'like', '%'. $search .'%')
                        ->orWhere('orders.name', 'like', '%'. $search .'%')
                        ->orWhere('orders.lastname', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count(); // count total rows
        
        if( $order )
        {   
            $columnas = ['folio_text' => 'id','invoice_badge' => 'invoice_require', 'payment_badge' =>'payment_status','status_badge' =>'status','date_created' => 'created_at','total_money' => 'total'];
            if(array_key_exists($order['column'], $columnas)) $order['column'] = $columnas[$order['column']];
            $query = $query->orderBy($order['column'], $order['dir']);
        }
        
        $query = $query->skip(($start-1)*$perPage)->take($perPage);
        $page = ($start-1)+$perPage/$perPage;

        $query = $query->select('orders.id',
                                'orders.invoice_require',
                                DB::raw('CONCAT(orders.name," ",orders.lastname) as fullname'),
                                'orders.created_at',
                                'orders.payment_status',
                                'orders.status',
                                'orders.total')->get();
                      
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


    public static function listaExportado($status, $payment){
        if($payment != '' && $status != ''){
            $query = Order::where('orders.payment_status', $payment)
                            ->where('orders.status', $status)
            ->get();
        }
        if($status == '' &&$payment != ''){           
            $query = Order::where('orders.payment_status', $payment)
            ->get();
        }
        if($status != '' && $payment == '' ){
            $query = Order::where('orders.status', $status)
            ->get();
        }
       
        if($status == '' && $payment == ''){
            $query = Order::all();
        }

        return $query;
    }

    public function changePaymentStatus(){
        $this->payment_status = !$this->payment_status;
        $this->save();
        return true;
    }

    public function changeStatus($new_status)
    {
        if(in_array($new_status,self::$status)) return false;
        $this->status = intval($new_status);
        $this->save();
        return true;
    }

    public function getAvailableProductsForRefund(){
        $refunds = Refunds::where('order_id', $this->id)->sum('quantity');
        $order_proudcts = OrderDetail::where('order_id', $this->id)->sum('quantity');

        return $order_proudcts - $refunds;
    }

    public function refunds(){
        return $this->hasMany('App\Models\Refunds', 'order_id');
    }

    public function getOrdersToRefund(){
        $config = Config::where('name', 'REFUNDS_DAYS')->first();
        $days = 0;
        
        if($config){
            $days = $config->value;

            $range = date("Y-m-d", strtotime( '-'.$days.' days' ) );

            return self::where('updated_at', '>=', $range)->where('status', 4);
        }
        return self::where('status', 4);
    }

    public function getRefundsDaysRemains(){
        $config = Config::where('name', 'REFUNDS_DAYS')->first();
        $days = 0;
        
        if($config){
            $days = $config->value;
            $db = DB::table('orders')
                ->selectRaw($days.' - DATEDIFF(now(), updated_at) as days')
                ->where('id', $this->id)
                ->first();
            return $db->days;
        }
        return null;
    }

    /**
     * Retorna los pedidos que tengan mensajes nuevos
     * sin leer
     */
    public function scopeWithNewMessages($query){
        return $query->where('new_message', 1);
    }
    public static function haveReview($c,$p){
        $db = DB::table("orders As o")
            ->join("order_detail As od","o.id","=","od.order_id")
            ->selectRaw("COUNT(*) AS conteo")
            ->where('od.product_id', $p)->where('o.customer_id', $c)
            ->first();
        return $db->conteo;
    }

    public static function dashBoardMasPedidos(){
        return  DB::select("SELECT COUNT(*) AS total,p.id AS product_id, p.name AS product,p.sku,p.image
                FROM order_detail od 
                INNER JOIN orders o ON o.id = od.order_id
                INNER JOIN product p ON p.id = od.product_id
                    GROUP BY product_id,product,sku,image ORDER BY total DESC LIMIT 10");
    }

    public static function dashBoardMasPedidosStatus($status){
        return  DB::select("SELECT COUNT(*) AS total,p.id AS product_id, p.name AS product,p.sku,p.image  
                FROM order_detail od 
                INNER JOIN orders o ON o.id = od.order_id
                INNER JOIN product p ON p.id = od.product_id
                where o.payment_status = ".self::$payment_text[$status]." GROUP BY product_id,product,sku,image ORDER BY total DESC LIMIT 15");
    }

    /*public static function orderVentas(){
        return  DB::select("SELECT mes, SUM(orden) AS 'order', SUM(venta) AS 'venta' FROM (
                SELECT MONTH(created_at) AS 'mes', SUM(total) AS orden, 0 AS venta FROM orders
                    WHERE YEAR(created_at) = ".date("Y")." GROUP BY mes 
                UNION  ALL
                 SELECT MONTH(created_at) AS 'mes', 0 AS orden, SUM(total) AS venta FROM orders
                    WHERE payment_status = 1 AND YEAR(created_at) = ".date("Y")." GROUP BY mes  ) AS B
                GROUP BY mes ");
    }*/

    public static function orderVentas(){
        return  DB::select("SELECT mes, SUM(orden) AS 'order', SUM(venta) AS 'venta' FROM (
                ( SELECT MONTH(created_at) AS 'mes', SUM(total) AS orden, 0 AS venta FROM orders
                     GROUP BY mes ORDER BY created_at DESC LIMIT 6 )
                UNION  ALL
                 (SELECT MONTH(created_at) AS 'mes', 0 AS orden, SUM(total) AS venta FROM orders
                    WHERE payment_status = 1  GROUP BY mes ORDER BY created_at DESC LIMIT 6 ) ) AS B
                GROUP BY mes ");
    }

    public static function soldCategory(){
        return  DB::select("SELECT  sum(od.amount) AS total, c.name AS category, case when cp.name is null then '_' else  cp.name end As category_parent
            FROM order_detail od 
            INNER JOIN orders o ON o.id = od.order_id
            INNER JOIN product p ON p.id = od.product_id
            INNER JOIN category c ON c.id = p.category_id 
            left JOIN category cp ON cp.id = c.parent_id 
        WHERE o.payment_status = 1 GROUP BY category,category_parent ORDER BY total DESC ");
    }

    public static function visitsMonth(){
        return  DB::select("SELECT SUM(visits) As visits,YEAR(DATE) AS ano, MONTH(DATE) AS mes
                 FROM visits GROUP BY ano, mes  ORDER BY ano desc, mes asc  LIMIT 6");
    }





}
