<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\RequestRefund;
use App\Mail\RefundUpdated;
use App\Mail\RequestProd;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\AddressBook;
use App\Models\Favorite;
use App\Models\State;
use App\Models\Refunds;
use App\Models\Message;
use App\Models\Config;
use App\Models\OrderProgrammingViews;
use App\Models\OrderProgramming;
use App\Models\OrderProgrammingDetails;
use App\Models\RequestProduct;
use Validator;
use Redirect;
use App\Library\Carrito;
use Auth;
use \Session;
use App\Library\MercadoPago;
use Crypt;
use DB;
use PDF;


class CustomerController extends Controller
{
    public function __construct(){
        $this->middleware('auth:customer-web')->except('orderDetail', 'refundsProducts');
    }
    
    public function myAccount(Request $request){
        $customer= Auth::guard('customer-web')->user();
        $data['orders'] = $customer->orders;
        $data['filtro'] = "Todos";
        $key = $request->all();
        //
        if(array_key_exists("filtro", $key)){
            if($key["filtro"] == "Pagado"){
                $data['orders'] = $customer->orders->where('payment_status','=',1);
                $data['filtro'] = "Pagado";
            }
            if($key["filtro"] == "Pendiente"){
                $data['orders'] = $customer->orders->where('payment_status','=',0);
                $data['filtro'] = "Pendiente";
            }
        }

        $data['orders_programming'] = $customer->ordersProgramming;
        
        //dd($data['orders_programming']);
        
        $data['address_book'] = AddressBook::with('City')->with('State')->where('customer_id', $customer->id)->get();        
        $data['favorites'] = $customer->favorites;
        $data['states'] = State::pluck('name','id');
        //$data['orders'] = $customer->orders;//$customer->orders->where('payment_status','=',1);
        $data['refunds'] = $customer->refunds();
        $data['f_state_customer'] = DB::table('state')->where('id','=',Auth::guard('customer-web')->user()->f_state)->first();
        $data['f_city_customer'] = DB::table('city')->where('id','=',Auth::guard('customer-web')->user()->f_city)->first();
        return view('website.my-account',$data);
    }

    public function myAccountJAT(Request $request){
        $customer= Auth::guard('customer-web')->user();
        $data['orders'] = $customer->orders;
        $data['filtro'] = "Todos";
        $key = $request->all();
        //
        if(array_key_exists("filtro", $key)){
            if($key["filtro"] == "Pagado"){
                $data['orders'] = $customer->orders->where('payment_status','=',1);
                $data['filtro'] = "Pagado";
            }
            if($key["filtro"] == "Pendiente"){
                $data['orders'] = $customer->orders->where('payment_status','=',0);
                $data['filtro'] = "Pendiente";
            }
        }

        $data['orders_programming'] = $customer->ordersProgramming;
        
        //dd($data['orders_programming']);
        
        $data['address_book'] = AddressBook::with('City')->with('State')->where('customer_id', $customer->id)->get();        
        $data['favorites'] = $customer->favorites;
        $data['states'] = State::pluck('name','id');
        //$data['orders'] = $customer->orders;//$customer->orders->where('payment_status','=',1);
        $data['refunds'] = $customer->refunds();
        $data['f_state_customer'] = DB::table('state')->where('id','=',Auth::guard('customer-web')->user()->f_state)->first();
        $data['f_city_customer'] = DB::table('city')->where('id','=',Auth::guard('customer-web')->user()->f_city)->first();
        return view('website.jat.my-account',$data);
    }

    //Order Detail
    public function orderDetail($order,$email){ /*trabajando...*/

        $order = Order::where('id',$order)->where('email',$email)->firstOrFail();
        $data = [];
        $data['statuses'] = Order::$status;
        $data['payment'] = Order::$payment;
        $data['delivery_service'] = Order::$delivery_service;
        $data['order'] = $order;
        $data['from'] = Message::$from["CUSTOMER"];
        $data['customer'] = Customer::where('id', $order->customer_id)->first();

        $payment = Payment::where('order_id',$order->id)->first();

        $payment_type_text = '';
        $payment = Payment::where('order_id', $order->id)->first();
        
        /*if($payment){
            if($payment->payment_type != '0')
            $payment_type_text = MercadoPago::$payment_types[$payment->payment_type];
        }*/

        $data['payment_type_text'] = $payment_type_text;

        $data["payment"] = $payment;
        
        $data['payment_type_text'] = $payment_type_text;
        
        $order->payment_status == 0 ? $data['link'] = MercadoPago::botonPago($order->id, $order->name." ".$order->last_name, 'Folio '.$order->folio(), $order->total, false, $order->email) : $data['link'] = '';
    	return view('website.payment-detail',$data);
    }

    public function refundsProducts()
    {   
        $customer= Auth::guard('customer-web')->user();
        $order = new Order();
        $config_days = Config::where('name', 'REFUNDS_DAYS')->first();
        
        $data = [];
        $data['reasons'] = DB::table('reasons_refund')->where('status',1)->get();
        $data['orders'] = $order->getOrdersToRefund()->where('customer_id', $customer->id)->get();
        $data['days'] = $config_days ? $config_days->value : 0;

    	return view('website.refunds-products',$data);
    }

    public function updateFavorites(Request $request,$product)
    {      
        $data['is_logged'] = true;
        if(!Auth::guard('customer-web')->check()){
            return response()->json(['is_logged' => false]);
        }
        $hashtag = '';
        $product = Product::find($product);
        if($request->has('hashtag')){ $hashtag = $request->hashtag;}
        if($product){
            $fav = Auth::guard('customer-web')->user()->favorites->whereIn('product_id',$product)->first();
            if(!$fav){//Si no se encontro producto en favoritos se agrega
                $favorite = new Favorite();
                $favorite->customer()->associate(Auth::guard('customer-web')->user());
                $favorite->product()->associate($product);
                $favorite->save();
                $data['title'] = 'Listo!';
                $data['message'] = 'Se añadio producto a favoritos';
                $data['status'] = 'success';
                $data['isFavorite'] = true;
                return response()->json($data);
            }else
            {//si se encontro, se quita de favoritos
                $name = $fav->product->name;
                $fav->delete();
                if($hashtag==''){
                    $data['title'] = 'Listo!';                    
                    $data['status'] = 'success';
                    $data['message'] = 'Se eliminó correctamente';
                    $data['isFavorite'] = false;
                    return response()->json($data);
                }
                session()->flash('flash_title','Listo!');
                session()->flash('flash_type','success');
                session()->flash('flash_message_favorite','Se eliminó '.$name.' correctamente');
                return redirect()->to(app('url')->previous().$hashtag);
            }
        }
        $data['title'] = 'Error';
        $data['message'] = 'Producto no encontrado';
        $data['status'] = 'danger';
        return response()->json($data);
    }

    public function saveAddress(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, 
            [   
                'address_name' => 'required',
                'name' => 'required',
                'lastname' => 'required',
                'address' => 'required',
                'between_streets' => 'required',
                'street_number' => 'required',
                'neighborhood' => 'required',
                'zipcode' => 'required|numeric|digits:5',
                'state' => 'required',
                'city' => 'required'
            ],
            [   
                'address_name.required' => 'Introduzca un nombre para la dirección',
                'name.required' => 'Introduzca el nombre',
                'lastname.required' => 'Introduzca los apellidos',
                'address.required' => 'Introduzca la dirección',
                'between_streets.required' => 'Introduzca entre calles',
                'street_number.required' => 'Introduzca el número',
                'neighborhood.required' => 'Introduzca la colonia',
                'zipcode.required' => 'Introduzca el código postal',
                'zipcode.digits' => 'El CP debe contener sólo 5 dígitos',
                'state.required' => 'Seleccione un estado',
                'city.required' => 'Seleccione una ciudad'
            ]
        );
        $validator->validate();

        /* Get session user */
        $customer = Auth::guard('customer-web');
        // New record
        if(!$request->id){
            $address_book = new AddressBook();

        // Edit record
        }else{
            $address_book = AddressBook::where('id', $request->id)->first();
            
            if(!$address_book){
                return Redirect::route('my-account')->withError("Un-Authorise access");
            }
    
            if($address_book->customer_id != $customer->user()->id){
                return Redirect::route('my-account')->withError("Un-Authorise access");
            }
            // return Redirect::route('home')->withError("Un-Authorise access");
        }

        $address_book->customer()->associate($customer->user());
        $address_book->address_name = $request->address_name;
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
        if($save){
            session()->flash('flash_title','Listo!');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Dirección alcamenada correctamente');
            return redirect()->route('my-account')->with('address_tab', 'true');
        }
    }

    public function delAddress($id){
        $customer = Auth::guard('customer-web');
        $address = AddressBook::where('id', $id)->first();
        
        if(!$address){
            return Redirect::route('my-account')->withError("Un-Authorise access");
        }

        if($address->customer_id != $customer->user()->id){
            return Redirect::route('my-account')->withError("Un-Authorise access");
        }


        $delete = $address->delete();
        if($delete){
            session()->flash('flash_title','Listo!');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Dirección eliminada correctamente');
            return redirect()->route('my-account')->with('address_tab', 'true');
        }

    }

    function getAllProductsDescription($details){
        $desc = '';
        foreach($details as $detail){
            $desc .= $detail->quantity." ".$detail->product->name.', ';
        }

        return rtrim($desc, ', ');
    }

    function updateCustomerProfile(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, 
            [   
                'name' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'f_zipcode' => 'required|numeric|digits:5',
                'f_name' => 'required',
                'f_rfc' => 'required',
                'f_address' => 'required',
                //'f_email' => 'required_if:factura_requiere,on|max:60',
                'f_phone' => 'required_if:factura_requiere,on|max:30',
                'f_city' => 'required',
                'f_state' => 'required',
            ],
            [   
                'name.required' => 'Introduzca el nombre',
                'lastname.required' => 'Introduzca los apellidos',
                'email.required' => 'Introduzca el email',
                'f_city' => 'Seleccion Ciudad',
                'f_state' => 'Seleccion Estado',
            ]
        );
        $validator->validate();
         /* Get session user */
         $customer = Customer::where('id', Auth::guard('customer-web')->user()->id)->first();

        $customer->name = $request->name;
        $customer->lastname = $request->lastname;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->cell_phone = $request->cell_phone;
        $customer->f_name = $request->f_name;
        $customer->f_rfc = $request->f_rfc;
        $customer->f_address = $request->f_address;
        $customer->f_zipcode = $request->f_zipcode;
        $customer->f_state = $request->f_state;
        $customer->f_city = $request->f_city;

        $save = $customer->save();
        if($save){
            session()->flash('flash_title','Listo!');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Información actualizada');
            return redirect()->route('my-account');
        }

    }

    public function refunds(Request $request){
        $saved = false;

        if(!$request->product_id){
            session()->flash('flash_title','Error!');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','No se seleccionó ningún producto');
            return redirect()->route('my-account-tab-refunds');
        }

        
        if($request->product_id){
            foreach($request->order_id as $order_val){
                $order_id = Crypt::decrypt($order_val);
                
                if(isset($request->product_id[$order_val])){

                    foreach($request->product_id[$order_val] as $index => $val){

                        $order_detail = OrderDetail::where('order_id', $order_id)->where('product_id', $val)->first();
    
                        if($request->quantity[$order_val][$index] <= $order_detail->getAvailableProductsForRefund()){
    
                            $refund = new Refunds();
    
                            $refund->order_id   = $order_id;
                            $refund->product_id = $request->product_id[$order_val][$index];
                            $refund->quantity   = $request->quantity[$order_val][$index];
                            $refund->reason_id  = $request->reason_id[$order_val][$index];
                            // $refund->comment    = $request->another[$order_val][$index];
                            $refund->comment_admin    = "Estamos procesando tu solicitud, en breve nos comunicaremos con usted";
                            $refund->status     = 2;
    
                            if($refund->save()){
                                $saved = true;
                            }
    
                        }
                    }
                }
                
            }
        }



        //dd($refund);

        /** Verfify is all insert success */
        if($saved){
            session()->flash('flash_title','Exito!');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Devolución creada, se ha enviado un correo al vendedor');

            /*aquí hay que hacer el envio del correo de devolución trabajando... */
            $contact = Config::where("name","EMAIL_ORDERS")->first();
            Mail::to($contact->value)->send(new RequestRefund($refund));

           
            Mail::to($refund->orders->email)->send(new RefundUpdated($refund,""));
        }
        else{
            session()->flash('flash_title','Error!');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','Ocurrió un error');
        }
        
        return redirect()->route('my-account-tab-refunds');
        

    }

    public function deleteRefunds(Request $request){
        $saved = false;

        if($request->refund_id){
            $refund = Refunds::where('id', $request->refund_id)->first();
            $refund->status = 0;
            $refund->quantity = 0;
        }


        /** Verfify is all insert success */
        if($refund->save()){
            session()->flash('flash_title','Exito!');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Se eliminó la devolución');
        }
        else{
            session()->flash('flash_title','Error!');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','Ocurrió un error');
        }
        
        return redirect()->route('my-account-tab-refunds');
    }

    public function exportOrderDetailPDF(Request $request){
        $order = Order::findOrFail($request->id);
        $pdf = PDF::loadView('admin.order.pdf', ['order' => $order]);
        $pdf->setPaper('A4', 'portrait');
        //return View::make('admin.order.pdf', ['order' => $order]);
        return $pdf->stream('Pedido '.$order->folio());    
    }

    public function sendRequestProduct(Request $request){
        if(Auth::guard('customer-web')->check()){
            
            $customer = Auth::guard('customer-web')->user();
            $nameCustomer = $customer->name." ".$customer->lastname;
            //dd($customer);
            $requestProduct = new RequestProduct();
            $requestProduct->name = $request->name;
            $requestProduct->description = $request->description;
            $requestProduct->id_customer = $customer->id;
            $requestProduct->name_customer = $nameCustomer;
            $requestProduct->created_at = date('Y-m-d H:i:s');
            $requestProduct->updated_at = date('Y-m-d H:i:s');
            $save = $requestProduct->save();

            if($save){
                $contact = Config::where("name","EMAIL_ORDERS")->first();
                Mail::to($contact->value)->send(new RequestProd($requestProduct,$customer));

                session()->flash('flash_title','Listo!');
                session()->flash('flash_type','success');
                session()->flash('flash_message','Información Enviada!!');
                return redirect()->route('my-account');
            }
        }else{
            session()->flash('flash_title','Error!');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','Ocurrió un error');
            return redirect()->route('my-account');
        }
    }

    //Order Detail
    public function programmingDetail($order){ /*trabajando...*/
        
        $orders = OrderProgramming::where('id',$order)->firstOrFail();
 
        $orderDet = OrderProgrammingDetails::where('order_programming_id',$order)->get();
 
        $data = [];
        $data['statuses'] = OrderProgramming::$status;
        $data['payment'] = OrderProgramming::$payment;
   
        $data['order'] = $orders;
        $data['orderDet'] = $orderDet;
        $data['customer'] = Auth::guard('customer-web')->user();
        //dd($orders);
        //$payment_type_text = '';
        //$payment = Payment::where('order_id', $order->id)->first();
        
        /*if($payment){
            if($payment->payment_type != '0')
            $payment_type_text = MercadoPago::$payment_types[$payment->payment_type];
        }*/

        //$data['payment_type_text'] = $payment_type_text;

        //$data["payment"] = $payment;
        
        //$data['payment_type_text'] = $payment_type_text;
        
    	return view('website.programming-detail',$data);
    }
}