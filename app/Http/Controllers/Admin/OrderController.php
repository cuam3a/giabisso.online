<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\OrderUpdated;
use App\Mail\OrderCreated;
use App\Mail\RefundUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Validator;
use Auth;
use Excel;
use DB;
use Cart;
use App\Library\Carrito;
use Carbon\Carbon;
use App\Library\MercadoPago;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Refunds;
use App\Models\AddressBook;
use App\Models\Config;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\OrderProgrammingViews;
use App\Models\OrderProgramming;
use App\Models\OrderProgrammingDetails;
use \Session;
use PDF;
use View;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin-web')->except(['createOrder']);
	}
    
    //List of orders
    public function listOrders()
    {
        $data = [];
        $data['statuses'] = Order::$status;
        $data['payment'] = Order::$payment;
    	return view('admin.order.list',$data);
    }

     //List of orders
     public function listRefunds()
     {
         $data = [];
         $data['status'] = Refunds::$status;
         $data['refunds'] = Refunds::orderBy('created_at', 'desc')->get();
         return view('admin.order.list-refunds',$data);
     }
 

	//Order Detail
    public function detail(Order $order){  
        $data                     = [];
        $data['statuses']         = Order::$status;
        $data['payment']          = Order::$payment;
        $data['delivery_service'] = Order::$delivery_service;
        $data['order']            = $order;
        
        $payment_type_text = '';
        $payment = Payment::where('order_id', $order->id)->first();
        if($payment){
            if($payment->payment_type != '0')
                $payment_type_text = MercadoPago::$payment_types[$payment->payment_type];

        }
        
        $data['payment_type_text'] = $payment_type_text;
        
    	return view('admin.order.detail',$data);
    }
    
    /* Return json of orders */
    public function ajaxOrders(Request $request)
    {
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];
        if($order['dir'] == null){ $order['dir'] = 'desc';$order['column'] = 'orders.id';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["Status" => "","Payment" => "","generalSearch" => null];
        $payment = array_key_exists('Payment',$query) ? $query['Payment']: "";
        $status = array_key_exists('Status',$query) ? $query['Status']: "";
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";

        //Obtener datos dependiendo a los parametros
        $data = Order::listado($perPage, $pages, $start, $search, $order, $payment, $status);
        return response()->json($data);
    }

    //Change status of order
    public function changeStatus(Request $request, Order $order)
    {   
        if(!$order->changeStatus($request->statusnum)){
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Orden '.$order->folio()); //<--FLASH MESSAGE
            session()->flash('flash_message','Estatus no válido, intente de nuevo.'); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Orden '.$order->folio()); //<--FLASH MESSAGE
            session()->flash('flash_message','Se actualizó pedido con estatus '.$order->getStatusTextAttribute()); //<--FLASH MESSAGE
            Mail::to($order->email)->send(new OrderUpdated($order));
        }
        return redirect()->back();
    }

    //Change status order payment status
    public function changePaymentStatus(Request $request, Order $order)
    {
        $order->changePaymentStatus();
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Orden '.$order->folio()); //<--FLASH MESSAGE
        session()->flash('flash_message','Se actualizó pedido con estatus de pago a '.$order->getPaymentTextAttribute()); //<--FLASH MESSAGE
        Mail::to($order->email)->send(new OrderUpdated($order));
    	return redirect()->back();
    }

    //Save order shipping details
    public function saveShippingDetails(Request $request){  /*trabajando...*/

        $order = Order::find(intval($request->id));
        $order->delivery_service = $request->delivery_service;
        $request->delivery_service == 0 ? $order->tracking_number = '' : $order->tracking_number = $request->tracking_number;
        $order->save(); /*aqui guardar el número de rastreo y el tipo de paqueteria*/

        Mail::to($order->email)->send(new OrderUpdated($order));
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Orden '.$order->folio()); //<--FLASH MESSAGE
        session()->flash('flash_message','Se actualizaron detalles de envio'); //<--FLASH MESSAGE
    	return redirect()->back();
    }

    public function createOrder(Request $request){
        $data = Input::all();
        $customer = Auth::guard('customer-web');
        if($customer->check()){
            $customer = $customer->user();
            $data['email'] = $customer->email;
        }else{
            $customer = '';
        }
        
        $available = $customer->getAvailable();
        if($customer->credit_status == 0 || $available < floatval(Cart::total(2,null,''))){
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Problemas con su Credito'); //<--FLASH MESSAGE
            session()->flash('flash_message','Su credito esta INACTIVO o no tiene Saldo Disponible'); //<--FLASH MESSAGE
            return redirect()->back();
        }

        if(floatval(Cart::total(2,null,''))==0){
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Ocurrio un error revise su pedido'); //<--FLASH MESSAGE
            session()->flash('flash_message','Total de compra igual a $0.00'); //<--FLASH MESSAGE
            return redirect()->back();
        }

        $validator_array = [
            'name' => 'required|max:150',
            'lastname' => 'required|max:120',
            'email' => 'required|email|max:60',
            'phone' => 'max:30',
            'cell_phone' => 'required|max:30',
            'address' => 'required|max:100',
            'street_number' => 'required|max:20',
            'between_streets' => 'required|max:150',
            'neighborhood' => 'required|max:150',
            'city' => 'required|numeric',
            'state' => 'required|numeric',
            'zipcode' => 'required|numeric|digits:5',
            'f_name' => 'required_if:factura_requiere,on|max:250',
            'f_rfc' => 'required_if:factura_requiere,on|max:30',
            'f_address' => 'required_if:factura_requiere,on|max:250',
            'f_email' => 'required_if:factura_requiere,on|max:60',
            'f_phone' => 'required_if:factura_requiere,on|max:30',
            'f_city' => 'required_if:factura_requiere,on|numeric',
            'f_state' => 'required_if:factura_requiere,on|numeric',
        ];
        
        // Validate address from form
        if($request->is_registered_address == 0){
            { //validaciones
                $validator = Validator::make($data, $validator_array);
                
                $validator->sometimes('f_estado', 'required_without:f_estado_pais', function ($input){
                    return ($input->factura_requiere == "on");
                });
                $validator->sometimes('f_ciudad', 'required_without:f_ciudad_pais', function ($input){
                    return ($input->factura_requiere == "on");
                });
                $validator->sometimes('f_estado_pais', 'required_without:f_estado', function ($input){
                    return ($input->factura_requiere == "on");
                });
                $validator->sometimes('f_ciudad_pais', 'required_without:f_ciudad', function ($input){
                    return ($input->factura_requiere == "on");
                });
                $validator->validate();
               
            }
        }
        
        /* Selected Address, get data from DB */
        if(Auth::guard('customer-web')->check() && $request->is_registered_address == 1){
            $address = AddressBook::where([
                                ['id',$request->selected_address],
                                ['customer_id', Auth::guard('customer-web')->user()->id]
                            ])->first();
            if($address){
                $data = $address->toArray();
                $data['email'] = Auth::guard('customer-web')->user()->email;
                $data['f_city'] = $request->f_city;
                $data['f_state'] = $request->f_state;
            }
        }
        
        /* Logged and save new address */
        if(Auth::guard('customer-web')->check() && $request->is_registered_address == 0){
            $this->saveAddress($request, Auth::guard('customer-web')->user());
        }

        $order = new Order();
        $columns = $order->getFillables();
        foreach ( $columns as $field ) {
            if ( array_key_exists($field, $data) ) {
                $order->$field = $data[$field];
            }
        }
        $new_customer = Customer::where('id', $order->customer_id)->first();
        //$order->invoice_require = ($order->invoice_require == "on") ? 1 : 0;
        $order->invoice_require = 1;
        $order->subtotal = floatval(Cart::subtotal(2,null,''));
        $order->shipping = floatval(Session::get('shipping'));
        $order->total = floatval(Session::get('total'));
        $order->shipment_id = Session::get('shipment_id');
        $order->f_name = $new_customer->f_name;
        $order->f_rfc = $new_customer->f_name;
        $order->f_email = $new_customer->f_name;
        $order->f_address = $new_customer->f_address;
        $order->f_phone = $new_customer->f_phone;
        $order->f_city = $new_customer->f_city;
        $order->f_state = $new_customer->f_state;
        if($customer) $order->customer()->associate($customer);
        $order->save();//se guarda pedido

        $products = Cart::content()->groupBy('id')->keys()->all();
        $products = Product::findMany($products);//Se obtienen productos en modelo

        $quantities = collect(Cart::content()->toArray())->mapWithKeys(function ($item) {
            return [$item['id'] => $item['qty']];//se obtienen cantidades 'id' => 'cantidad'
        });
        //GCUAMEA
        $prices = collect(Cart::content()->toArray())->mapWithKeys(function ($item) {
            return [$item['id'] => $item['price']];//se obtienen cantidades 'id' => 'cantidad'
        });

        foreach($products as $product){
            $detail = new OrderDetail(); // Will update the quantity
            $detail->sku = $product->sku;
            $detail->name = $product->name;
            $detail->category = $product->category->name;
            $detail->brand = $product->brand;
            $detail->unit_price = $prices[$product->id];//$product->final_price;
            $detail->quantity = $quantities[$product->id];
            $detail->amount = $prices[$product->id]*$quantities[$product->id];
            $detail->order()->associate($order);
            $detail->product()->associate($product);
            $detail->save();

            // Stock substraction
            $product->stock = $product->stock - $quantities[$product->id];
            $product->save();
        }
        $insertedId = $order->id;
        $conf = [];
        $conf["order"] = Order::where('id', $insertedId)->first();
        
        $new_order = Order::where('id', $insertedId)->first();

        //dd(new OrderCreated($new_order));
        Mail::to($order->email)->send(new OrderCreated($new_order, $new_customer));
        
        //dd(Config::where('name', 'EMAIL_ORDERS')->first());
        $payment_email = Config::where('name', 'EMAIL_ORDERS')->first();
        if($payment_email)
            Mail::to($payment_email->value)->send(new OrderCreated($new_order, $new_customer));

        if(Auth::guard('customer-web')->check()){
            Auth::guard('customer-web')->user()->shoppingCart->delete();
        }
        Cart::destroy();
        $request->session()->forget('shipping');
        return redirect()->route('payment-detail',['order' => $order->id,'email' => $order->email, 'payment' => $order->payment]);
    }

    public function saveAddress($request){
        $address_book = new AddressBook();
        $address_book->customer()->associate(Auth::guard('customer-web')->user());
        $address_book->address_name = 'DIRECCIÓN';
        $address_book->name = $request->name;
        $address_book->lastname = $request->lastname;
        $address_book->phone = $request->phone;
        $address_book->cell_phone = $request->cell_phone;
        $address_book->address = $request->address;
        $address_book->between_streets = $request->between_streets;
        $address_book->street_number = $request->street_number;
        $address_book->suit_number = $request->suit_number;
        $address_book->neighborhood = $request->neighborhood;
        $address_book->zipcode = $request->zipcode;
        $address_book->state = $request->state;
        $address_book->city = $request->city;
        $address_book->instructions_place = $request->instructions_place;

        $save = $address_book->save();
    }

    //Export excel 'Pedidos'
    public function exportOrders(){
        $status = Input::get('estatus');
        $payment = Input::get('pago');
        $data = Order::listaExportado($status, $payment);
       
        return Excel::create('JAT-Pedidos', function($excel) use ( $data) {
            $excel->setCreator('Justo a Tiempo')->setCompany('Justo a Tiempo');
            $excel->sheet('Listado de pedidos', function($sheet) use ( $data) {
                $sheet->loadView('admin.order.export',[
                    'orders' => $data
                ]);
            });
        })->download('xls');
    }

    public function saveRefund(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, 
            [
                'refund_id' => 'required',
                'status' => 'required'
            ]
        );
        
        $refund = Refunds::where('id', $request->refund_id)->first();

        $refund->status = $request->status;
        $refund->comment_admin = $request->comment_admin;

        $refund->save();

        if(!$refund){
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Ocurrió un error'); //<--FLASH MESSAGE
            session()->flash('flash_message','Error'); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Aviso'); //<--FLASH MESSAGE
            session()->flash('flash_message','Se actualizó la devolución'); //<--FLASH MESSAGE

            $instructions = Config::where("name","REFUNDS_INSTRUCTIONS")->get();
            //dd($instructions);
            Mail::to($refund->orders->email)->send(new RefundUpdated($refund,$instructions[0]->value));
        }
        return redirect()->route('admin-refunds-list');
    }

    public function exportOrderDetailPDF(Request $request){
        $order = Order::findOrFail($request->id);
        $pdf = PDF::loadView('admin.order.pdf', ['order' => $order]);
        $pdf->setPaper('A4', 'portrait');
        //return View::make('admin.order.pdf', ['order' => $order]);
        return $pdf->stream('Pedido '.$order->folio());    
    }

    //GCUAMEA Programacion de Pedidos
    /*public function createOrderProgramming(){
        if(Auth::guard('customer-web')->check()){
            $customer = Auth::guard('customer-web')->user();
            $orderProgrammingView = OrderProgrammingViews::where('customer_id','=',$customer->id)
                ->where('status','=',0)->get();

            $orderProgramming = new OrderProgramming();
            $orderProgramming->customer_id = $customer->id;
            $orderProgramming->name = $customer->name + " " + $customer->lastname;
            $orderProgramming->status = 0;
            $orderProgramming->save();

            foreach($orderProgrammingView as $item){
                $detail = new OrderProgrammingDetails(); // Will update the quantity
                $detail->order_programming_id = $orderProgramming->id;
                $detail->product_id = $item->product_id;
                $detail->sku = $item->sku;
                $detail->name = $item->name;
                $detail->category = $item->category;
                $detail->unit_price = $item->unit_price;
                $detail->quantity = $item->quantity;
                $detail->date_send = $item->date_send;
                $detail->status = $item->status;
                $detail->save();

                $orderProgramming_db = OrderProgrammingViews::where('id', $item->id)->first();
                $orderProgramming_db->status = 1;
                $orderProgramming_db->save();
            }
            session()->flash('flash_type','success');
            session()->flash('flash_message','Pedido Enviado Correctamente.');
            return  redirect()->back();
        }else{
            session()->flash('flash_title','Error');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','Recuerde Iniciar Sesion antes de agregar un producto');
            return redirect()->back();
        }
    }*/

    public function listProgrammingOrders()
    {
        $data = [];
        $data['statuses'] = OrderProgramming::$status;
        $data['payment'] = OrderProgramming::$payment;
    	return view('admin.order.list-programming',$data);
    }

    public function ajaxProgrammingOrders(Request $request)
    {
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];
        
        if($order['dir'] == null){ $order['dir'] = 'desc';$order['column'] = 'order_programming.id';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["Status" => "","Payment" => "","generalSearch" => null];
        $payment = array_key_exists('Payment',$query) ? $query['Payment']: "";
        $status = array_key_exists('Status',$query) ? $query['Status']: "";
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";
        
        //Obtener datos dependiendo a los parametros
        $data = OrderProgramming::listado($perPage, $pages, $start, $search, $order, $payment, $status);
        return response()->json($data);
    }

    public function detailProgramming(OrderProgramming $order){  
        //dd($order);
        $data                     = [];
        $data['statuses']         = OrderProgramming::$status;
        $data['order']            = $order;
        $data['order_items']      = OrderProgrammingDetails::where('order_programming_id','=',$order->id)->orderBy('date_send', 'asc')->get();
        
        $data['payment_type_text'] = "";
        
    	return view('admin.order.detail-programming',$data);
    }

    public function changeStatusProgramming(Request $request, Order $order)
    {   
        if(!$order->changeStatus($request->statusnum)){
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Orden '.$order->folio()); //<--FLASH MESSAGE
            session()->flash('flash_message','Estatus no válido, intente de nuevo.'); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Orden '.$order->folio()); //<--FLASH MESSAGE
            session()->flash('flash_message','Se actualizó pedido con estatus '.$order->getStatusTextAttribute()); //<--FLASH MESSAGE
            Mail::to($order->email)->send(new OrderUpdated($order));
        }
        return redirect()->back();
    }

    public function updateDetailsProgramming(Request $request)
    {   
        $orderProgramming = collect($request->all());
        $idOrderProgramming = 0;
        $orderProgramming->forget('_token');
        $orderProgramming = $orderProgramming->groupBy(function ($item, $key) {
            if(strpos($key,'-')){
                $value = explode('-',$key);
                if(is_numeric(intval($value[1]))){
                return ['id' => $value[1]];
                }
            }          
            return null;
        });
        $orderProgramming->forget('');

        foreach($orderProgramming as $orderProgramming => $val){
            $orderProgramming_db = OrderProgrammingDetails::where('id', $orderProgramming)->first();
            $orderProgramming_db->status = $val[0];
            $orderProgramming_db->save(); 
            $idOrderProgramming = $orderProgramming_db->order_programming_id;
        }

        if($idOrderProgramming != 0){
            $orderProg = OrderProgramming::where('id','=',$idOrderProgramming)->first();
            $count = OrderProgrammingDetails::where('order_programming_id','=',$idOrderProgramming)->count();
            $countPend = OrderProgrammingDetails::where('order_programming_id','=',$idOrderProgramming)->where('status','=',0)->count();
            $countProc = OrderProgrammingDetails::where('order_programming_id','=',$idOrderProgramming)->where('status','=',1)->count();
            $countCanc = OrderProgrammingDetails::where('order_programming_id','=',$idOrderProgramming)->where('status','=',2)->count();
            $countEntr = OrderProgrammingDetails::where('order_programming_id','=',$idOrderProgramming)->where('status','=',3)->count();

            if($count == $countPend || $count == $countCanc + $countPend){
                $orderProg->status = 0;
                $orderProg->save();
            }

            if(($count >= $countProc && $countProc > 0) ){
                $orderProg->status = 1;
                $orderProg->save();
            }

            if($count == $countEntr || $count == $countCanc + $countEntr){
                $orderProg->status = 3;
                $orderProg->save();
            }

            if($count == $countCanc){
                $orderProg->status = 2;
                $orderProg->save();
            }
        }
        

        session()->flash('flash_type','success');
        session()->flash('flash_message','Pedido Actualizado Correctamente.');
        return redirect()->back();
    }

    public function cancelOrderProgramming(OrderProgramming $order){
        $orderProgramming_db = OrderProgramming::where('id', $order->id)->first();
        $orderProgramming_db->status = 2;
        $orderProgramming_db->save(); 

        $orderProgrammingdetails_db = OrderProgrammingDetails::where('order_programming_id', $orderProgramming_db->id)->get();
        foreach($orderProgrammingdetails_db as $item){
            $item->status = 2;
            $item->save(); 
        }

        session()->flash('flash_type','success');
        session()->flash('flash_message','Pedido Cancelado Correctamente.');
        return redirect()->back(); 
    }
}
