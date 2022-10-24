<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderUpdated;
use App\Models\Category;
use App\Models\CategoryViews;
use App\Models\CategoryCustomerViews;
use App\Models\Product;
use App\Models\ProductViews;
use App\Models\ProductCustomerViews;
use App\Models\Config;
use App\Models\State;
use App\Models\City;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Content;
use App\Models\AddressBook;
use App\Models\Faq;
use App\Models\Carousel;
use App\Models\Payment;
use App\Models\Admin;
use App\Models\ShipmentTypes;
use App\Models\QuestionAnswer;
use App\Models\Review;
use App\Models\ShoppingCart;
use App\Models\PricesLists;
use App\Models\PricesListProducts;
use App\Models\OrderProgrammingViews;
use App\Models\OrderProgramming;
use App\Models\OrderProgrammingDetails;
use Validator;
use Redirect;
use \Session;
use Share;
use DB;
use Auth;
use App\Models\Slider;
use Cart;
use App\Library\Calculate;
use App\Library\Carrito;
use App\Library\MercadoPago;
use App\Mail\Contact;
use App\Mail\OrderProgrammingCreated;
use PDF;



class WebsiteController extends Controller
{
    public function __construct(){   
    }

    //Login to Backoffice
    public function index(Request $request){
        $data['offers'] = Product::all()->take(12);
        $data['newest'] = Product::all()->take(12);
        $data['recomended'] = Product::all()->take(12);          
        $data['byCategory'] = Product::all()->take(12); 
        $data['carousels'] = Carousel::orderBy('order')->get();
        $data['sliders'] = Slider::getSlidersRandom();
        $data['countOrderProg'] = 5;
        $data['price_customer'] = null;
        return view('website.home',$data);
    }


    public function productDetail(Product $id){
        if($id->status == Product::$statusWP['Inactivo']) return redirect('');
        $data['webtitle'] = $id->name;
        $data['product'] = $id;
        $data['price_customer'] = null;
        
        //Guardado de vistas por producto
        $productViews = ProductViews::updateOrCreate(['product_id' => $data['product']->id,'category_id' => $data['product']->category_id]);
        $productViews->views += 1;
        $productViews->save();
        //Guardado de cuantas vcs cliente
        $data['valorar']              =  0 ;
        if(Auth::guard('customer-web')->check()){
            // vió un producto
            $customerViews = ProductCustomerViews::updateOrCreate(['product_id' => $data['product']->id,
                                                                    'category_id' => $data['product']->category_id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            $customerViews->views += 1;
            $customerViews->save(); 
            // vió categoria
            if($data['product']->parent_id == null){
                $customerCategoryViews = CategoryCustomerViews::updateOrCreate(['category_id' => $data['product']->category_id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            }
            
            $customerCategoryViews->views += 1;
            $customerCategoryViews->save();

            $data['valorar'] = Order::haveReview( Auth::guard('customer-web')->user()->id, $id->id );

            //GCUAMEA
            if(Auth::guard('customer-web')->check()){
                if(Auth::guard('customer-web')->user()->price_list_id != 0){
                    $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                    $data['price_customer'] = PricesListProducts::where('price_list_id','=',$priceListId)
                                                                    ->where('product_id','=',$data['product']->id)
                                                                    ->first();
                }
            }

        }
        //Guardado de vistas por categoría
        $categoryViews = CategoryViews::updateOrCreate(['category_id' => $data['product']->category_id]);
        $categoryViews->views += 1;
        $categoryViews->save();
        //
        $data['related_products'] = Product::where('status',1)->where('category_id', $id->category_id)->paginate(12);
        $data['maxQty'] = $id->stock;        

    
        //$data["my_review"]        = $id->myReview;
        //dd($data["my_review"]);

        $data['webtitle']         = $id->name;
        $data['product']          = $id;
        $data['related_products'] = Product::where('status',1)->where('category_id', $id->category_id)->paginate(12);
        $data['maxQty']           = $id->stock;        
        
        return view('website.product-detail', $data);
    }

    public function productDetailJAT(Product $id){
        if($id->status == Product::$statusWP['Inactivo']) return redirect('');
        $data['webtitle'] = $id->name;
        $data['product'] = $id;
        $data['price_customer'] = null;
        
        //Guardado de vistas por producto
        $productViews = ProductViews::updateOrCreate(['product_id' => $data['product']->id,'category_id' => $data['product']->category_id]);
        $productViews->views += 1;
        $productViews->save();
        //Guardado de cuantas vcs cliente
        $data['valorar'] =  0 ;
       
        if(Auth::guard('customer-web')->check()){
            // vió un producto
            $customerViews = ProductCustomerViews::updateOrCreate(['product_id' => $data['product']->id,
                                                                    'category_id' => $data['product']->category_id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            $customerViews->views += 1;
            $customerViews->save(); 
            // vió categoria
            if($data['product']->parent_id == null){
                $customerCategoryViews = CategoryCustomerViews::updateOrCreate(['category_id' => $data['product']->category_id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            }
            
            $customerCategoryViews->views += 1;
            $customerCategoryViews->save();

            $data['valorar'] = Order::haveReview( Auth::guard('customer-web')->user()->id, $id->id );

            //GCUAMEA
            if(Auth::guard('customer-web')->check()){
                if(Auth::guard('customer-web')->user()->price_list_id != 0){
                    $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                    $data['price_customer'] = PricesListProducts::where('price_list_id','=',$priceListId)
                                                                    ->where('product_id','=',$data['product']->id)
                                                                    ->first();
                }
            }

        }
        
        //Guardado de vistas por categoría
        $categoryViews = CategoryViews::updateOrCreate(['category_id' => $data['product']->category_id]);
        $categoryViews->views += 1;
        $categoryViews->save();
        //
        $data['related_products'] = Product::where('status',1)->where('category_id', $id->category_id)->paginate(12);
        $data['maxQty'] = $id->stock;        
        
    
        //$data["my_review"]        = $id->myReview;
        //dd($data["my_review"]);

        $data['webtitle']         = $id->name;
        $data['product']          = $id;
        $data['related_products'] = Product::where('status',1)->where('category_id', $id->category_id)->where('name',"!=",$id->name)->paginate(6);
        $data['maxQty']           = $id->stock;        
        //dd($data);
        return view('website.jat.product-detail', $data);
    }

    /**
     * params = Filtros o busqueda
     * slug   = categoria padre o unica categoria
     * child  = subcategoria(si se especifica)
     */
    public function categoryList(Request $params, $slug=null, $child=null)
    {   
        $cat_obj = new Category;
        $data['price_customer'] = null;
        $data['webtitle'] = 'Tienda';
        $data['slug'] = $slug;//Para marcar categoria activa
        $data['child'] = $child;
        $data['products'] = Product::filterSearch($slug, $child, $params);
        // $data['categories'] = Category::where('parent_id',null)->orderBy('order','ASC')->get();//Menu
        
        $data['categories'] = $cat_obj->categoriesWithProductsAndStock();
        
        $category = Category::where('slug', $slug)->first();
        if(Auth::guard('customer-web')->check() && isset($category->id) && $category->id != null){
            // vió categoria
            $customerCategoryViews = CategoryCustomerViews::updateOrCreate(['category_id' => $category->id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            $customerCategoryViews->views += 1;
            $customerCategoryViews->save();
        }

        //GCUAMEA
        if(Auth::guard('customer-web')->check()){
            if(Auth::guard('customer-web')->user()->price_list_id != 0){
                $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                $data['price_customer'] = PricesListProducts::where('price_list_id','=',$priceListId)->get();
                //dd($data['price_customer']);
            }
        }

        //Guardado de vistas por categoría
        if(isset($category->id) && $category->parent_id != null){
            $categoryViews = CategoryViews::updateOrCreate(['category_id' => $category->id]);
            $categoryViews->views += 1;
            $categoryViews->save();
        }
        //   
        $data['filterOrderBy'] = Product::$filterOrderBy;
        return view('website.product-list',$data);
    }

    public function categoryListJAT(Request $params, $slug=null, $child=null)
    {   
        $cat_obj = new Category;
        $data['price_customer'] = null;
        $data['webtitle'] = 'Tienda';
        $data['slug'] = $slug;//Para marcar categoria activa
        $data['child'] = $child;
        $data['products'] = Product::filterSearch($slug, $child, $params);
        // $data['categories'] = Category::where('parent_id',null)->orderBy('order','ASC')->get();//Menu
        
        $data['categories'] = $cat_obj->categoriesWithProductsAndStock();
        
        $category = Category::where('slug', $slug)->first();
        if(Auth::guard('customer-web')->check() && isset($category->id) && $category->id != null){
            // vió categoria
            $customerCategoryViews = CategoryCustomerViews::updateOrCreate(['category_id' => $category->id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            $customerCategoryViews->views += 1;
            $customerCategoryViews->save();
        }

        //GCUAMEA
        if(Auth::guard('customer-web')->check()){
            if(Auth::guard('customer-web')->user()->price_list_id != 0){
                $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                $data['price_customer'] = PricesListProducts::where('price_list_id','=',$priceListId)->get();
                //dd($data['price_customer']);
            }
        }

        //Guardado de vistas por categoría
        if(isset($category->id) && $category->parent_id != null){
            $categoryViews = CategoryViews::updateOrCreate(['category_id' => $category->id]);
            $categoryViews->views += 1;
            $categoryViews->save();
        }
        //   
        $data['filterOrderBy'] = Product::$filterOrderBy;
        return view('website.jat.product-list',$data);
    }

    public function faq(){
        $data['webtitle'] = 'Preguntas frecuentes';
        $data['email_support'] = Config::where('name', 'EMAIL_SUPPORT')->first();
        $data['faq'] = Faq::orderBy("order")->get();
        return view('website.faq',$data);
    }

    public function privacy(){
        $data['webtitle'] = 'Aviso de privacidad';
        return view('website.privacy',$data);
    }

    public function deliveryInfo(){//Shipping
        $customer = Auth::guard('customer-web')->user();
        $data['addresses'] = array();
        Auth::guard('customer-web')->check() ? $data['addresses'] = AddressBook::where('customer_id', $customer->id)->get() :  null;
        $data['webtitle'] = 'Datos de Envio';
        $data['cartBasket'] = Cart::content();
        $data['states'] = State::pluck('name','id');
        $data['free_shipment'] = Config::select('value')->where('name', 'FREE_SHIPMENT')->first();
        $data['amount'] = Config::select('value')->where('name', 'FREE_SHIPMENT_AMOUNT')->first();
        return view('website.delivery-info',$data);
    }

    public function aboutUs(){
        $data['webtitle'] = 'Nosotros';
        return view('website.about-us',$data);
    }
    public function contactUs(){
        $data['webtitle'] = 'Contacto';
        return view('website.contact-us',$data);
    }

    public function cart(){
        $data['webtitle'] = 'Pedido';//GCUAMEA
        $data['cartBasket'] = Cart::content();
        $data['maxQty'] = 10;
        $data['free_shipment'] = Config::select('value')->where('name', 'FREE_SHIPMENT')->first();
        $data['amount'] = Config::select('value')->where('name', 'FREE_SHIPMENT_AMOUNT')->first();
        $data['shoppingcart']=new ShoppingCart();
        return view('website.cart',$data);
    }

    /* Get cities */
    public function getCities(Request $request)
    {
        $state = $request->state;
        $cities = City::where('state_id',$state)->pluck('name', 'id');
        return response()->json($cities);
    }

    public function addProductToCart(Request $request, Product $product, $ban = false){   
        $cont = Cart::count();$cont++;

        $shipment_type;
        $shipment_cost;

        $msg_out_stock = '';

        $cart_content = Cart::content();

        $id = $product->id;
        $result = $cart_content->search(function ($cartItem, $rowId) use($id) {
            return $cartItem->id === $id;
        });

        if($result != ''){
            $cur_prod = Cart::get($result);
            if(($request->quantity + $cur_prod->qty) > $product->stock){
                $request->quantity = $product->stock;
                $msg_out_stock = "No pueden ser agregados más productos debido a que ya se alcanzó el límite de inventario";

                session()->flash('flash_title','Aviso');
                session()->flash('flash_type','warning');
                session()->flash('flash_message', $msg_out_stock.' <a href="'.route('cart').'">Ir al pedido</a>');
                return redirect()->back();
            }
        }else{
            if($request->quantity > $product->stock){
                $request->quantity = $product->stock;
                $msg_out_stock = "Sólo pudieron ser agregados $product->stock productos, debido a cambios en el inventario";
            }
        }
        

        if(!$product->shipment_id){
            $db_shipment_types = ShipmentTypes::where('default',1)->first();
            $shipment_type = $db_shipment_types->id;
            $shipment_cost = $db_shipment_types->cost;
        }
        else{
            $shipment_type = $product->shipment_id;
            $shipment_cost = $product->shipment_cost;
        }

        $priceItem = $product->final_price;
        //GCUAMEA
        if($product->liquidado != 1){
            if(Auth::guard('customer-web')->check()){
                $priceListId = Auth::guard('customer-web')->user()->price_list_id;
    
                if($priceListId){
                    $priceList = PricesListProducts::where('price_list_id','=',$priceListId)->where('product_id','=',$product->id)->first();
    
                    if($priceList->price < $priceItem){
                        $priceItem = $priceList->price;
                    }
                }
                
            }
        }else{
            $priceItem = $product->liquidado_price;
        }
        

        Cart::add(['id' => $product->id,
                    'name' => $product->name,
                    'qty' => $request->quantity,
                    'price' => $priceItem,
                    'options' => ['brand' => $product->brand,
                                    'image' => $product->main_image(),
                                    'product_id' => $product->id,
                                    'stock' => $product->stock,
                                    'slug' => $product->slug,
                                    'shipment_type' => $shipment_type,
                                    'shipment_cost' => $shipment_cost]]);
        session()->flash('flash_title','Listo!');
        session()->flash('flash_type','success');
        $cart = route('cart');
        Calculate::calculateShipping();
        Calculate::calculateTotal();

        if(Auth::guard('customer-web')->check()){
            Carrito::guardar(Auth::guard('customer-web')->user());
        }
        //   Session::put('shipping',floatval(Cart::total(2,null,'')) >= Order::$minPurchase ?  0 : Order::$shippingCost);
            if ( $ban ){
                session()->flash('flash_message','Producto agregado a pedido. '.$msg_out_stock.'');
                return redirect($cart);
            }
        session()->flash('flash_message','Producto agregado a pedido. '.$msg_out_stock.' <a href="'.$cart.'">Ir a pedido</a>');
        return  redirect()->back();
    }



    public function updateProductCart(Request $request){

        $products = collect($request->all());
        $products->forget('_token');
        $products = $products->groupBy(function ($item, $key) {
            if(strpos($key,'-')){
                $value = explode('-',$key);
                if(is_numeric(intval($value[1]))){
                return ['id' => $value[1]];
                }
            }          
            return null;
        });
        $products->forget('');
    
        foreach($products as $product => $val){

            $product_db = Product::where('id', $product)->first();
            
            if($val[1] <= $product_db->stock){
                // Send value less or equal to stock
                Cart::update($val[0], $val[1]); // Will update the quantity
                if (Auth::guard('customer-web')->check()) {         
                    Carrito::guardar(Auth::guard('customer-web')->user());
                }
            }
            else{
                session()->flash('flash_title','Aviso!');
                session()->flash('flash_type','warning');
                session()->flash('flash_message','La cantidad supera el total de inventario');
            }

        }

        Calculate::calculateShipping();
        Calculate::calculateTotal();
    //   Session::put('shipping',floatval(Cart::total(2,null,'')) >= Order::$minPurchase ?  0 : Order::$shippingCost);  
      return redirect()->back();
    }


    /* Función para eliminar producto de carrito*/
    public function deleteProductFromCart($id=0)
    {  
        $cart = Cart::content();
        $result = $cart->search(function ($cartItem, $rowId) use($id) {
            return $cartItem->rowId === $id;
        });

        if($result){
            Cart::remove($result);
            if (Auth::guard('customer-web')->check()) {  
                Carrito::guardar(Auth::guard('customer-web')->user());
            }
            Calculate::calculateShipping();
            Calculate::calculateTotal();
            // Session::put('shipping',floatval(Cart::total(2,null,'')) >= Order::$minPurchase ?  0 : Order::$shippingCost);
            return redirect()->back();
        }

        session()->flash('flash_title','Error');
        session()->flash('flash_type','warning');
        session()->flash('flash_message','Producto no encontrado en pedido');
        return redirect()->back();
    }

    public function policy(){
        $data['policy'] = Content::select('value')->where('name','DELIVERY_POLICY')->first();
        return view('website.policy', $data);
    }

    public function formatNumber($number){
        return "(".substr($number, 0, 3).") ".substr($number, 3, 3).".".substr($number,6,2).".".substr($number,8);
    }

    //WEBHOOK DE MERCADO PAGO
    public function notifications_mp(){
        // $respuesta = MercadoPago::revisarPeticion('payment', 17);
        // print_r($respuesta);
        // exit();
       // \Log::info("[MERCADOPAGO]: ". json_encode(Input::all()));
       $type = Input::get("topic");
       $id = Input::get("id");
       $cuenta = null;

    //    $id = '14631204';
    //    $type = 'merchant_order';

       \Log::info("[MERCADOPAGO]: INPUT ". json_encode(Input::all()));

    //    $respuesta = MercadoPago::revisarPeticion('merchant_order', '759158984');
       $respuesta = MercadoPago::revisarPeticion($type, $id);

       \Log::info("[MERCADOPAGO]: RESPUESTA ". json_encode($respuesta));

       //Si fue posible traer los datos
       if($respuesta["status"] == "success"){

           //Revisar tipo "merchant_order"
           if($type == MercadoPago::$tipo[2]){
               \Log::info("[MERCADOPAGO]: notificacion MERCHANT_ORDER".json_encode($respuesta));
               $order = Order::find($respuesta['id_pago']);
            //    $pedido = Pedido::find($respuesta['id_pago']);

               if($order){
                   $payment = Payment::firstOrNew(['mp_order' => $respuesta["response"]["id"]]);
                   \Log::info("[MERCADOPAGO]: Usa primero o ya creado?". json_encode($payment));
                   $payment->order()->associate($order);
                   $payment->amount = $respuesta["response"]["items"][0]["unit_price"];
                   $payment->mp_order = $respuesta["response"]["id"];
                   $payment->save();
               }
           }

           //Revisar tipo "payment"
           else if($type == MercadoPago::$tipo[1]){
                \Log::info("[MERCADOPAGO]: notificacion PAYMENT",$respuesta);
               $payment = Payment::where("mp_order", $respuesta["id_orden"])->first();

               if($payment){
                   \Log::info("[MERCADOPAGO]: if(pago)");
                   $payment->mp_payment = $respuesta["response"]["id"];
                   $payment->net_received_amount = $respuesta["response"]["net_received_amount"];
                   $payment->mp_method = $respuesta["response"]["payment_method_id"];
                   $payment->payment_type = $respuesta["response"]["payment_type"];
                   $payment->last_four_digits = isset($respuesta["response"]["last_four_digits"]) ? $respuesta["response"]["last_four_digits"] : '';
                   $payment->card_holder = isset($respuesta["response"]["cardholder"]) ? $respuesta["response"]["cardholder"]["name"] : '';
                   $payment->status = MercadoPago::$statusTxt[$respuesta["response"]["status"]];
                   $payment->save();

                    //Disparar evento de Pago Registrado
                    if($payment->status == MercadoPago::$statusTxt["approved"]){
                       \Log::info("[MERCADOPAGO]: approved");
                       $order = Order::find($payment->order_id);   
                       $order->payment_status = Order::$payment_text["pagado"];;
                       $order->payment_date = \Carbon\Carbon::now();
                       $order->save();

                        // Customer mail notification
                        Mail::to($order->email)->send(new OrderUpdated($order));

                        // Admin email notificaction
                        $admin = Admin::first();
                        if($admin){
                            Mail::to($admin->email)->send(new OrderUpdated($order));
                        }
                    }
                }
            }
        }
        print_r(json_encode($respuesta));
   }

    //PAGO SATISFACTORIO
    public function success(){

        return view('payments.success',[
            "auth" => "",
            "title" => "Pago Exitoso"
        ]);
    }

    //PROCESANDO PAGO
    public function processing(){

        return view('payments.pending',[
            "auth" => "",
            "title" => "Pago en Proceso"
        ]);
    }

    //PAGO CANCELADO
    public function canceled(){
        $id = Input::get("merchant_order_id");
        $link = "";
        $respuesta = MercadoPago::revisarPeticion("merchant_order", $id);

        if($respuesta["status"] == "success"){
            $order = Order::find($respuesta["id_pago"]);
            if($order){
                $link = route('payment-detail',['order' => $order->id, 'email'=>$order->email]);
            }
        }

        return view('payments.canceled',[
            "auth" => "",
            "title" => "Pago Cancelado",
            "link" => $link
        ]);
    }

    //ENVIO DE CORREO CONTACT FORM
    public function sendEmailContact(Request $request){
        $data_contact=[];
        $email_contact= Config::where('name', 'EMAIL_CONTACT')->first();
        $data = [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'email' => 'required|email'
        ];
        if(env('APP_ENV') != "dev"){
            $data['g-recaptcha-response'] = 'required|recaptcha';
        }
        //$validator = Validator::make($request->all(),$data,['g-recaptcha-response.required' => 'Captcha requerido']);   
        $validator = Validator::make($request->all(),$data); 
        //$validator->validate();


        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $bussines_name=Config::where('name', 'BUSINESS_NAME')->first();
        $data_contact['name']=$request->name;
        $data_contact['lastname']=$request->lastname;
        $data_contact['phone']=$request->phone;
        $data_contact['email']=$request->email;
        $data_contact['message']=$request->message;
        $data_contact['business_name']= ($bussines_name ? ' '.$bussines_name->value : '');



        try{
            Mail::to($email_contact->value)->send(new Contact($data_contact));
            session()->flash('flash_title','Mensaje enviado! ');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Su mensaje ha sido enviado a un administrador.');
                    
        }catch(\Exception $e){
            $error = $e->getMessage();
            //\Log::info("[WelcomeEmail]: ".$error);
            session()->flash('flash_title','Mensaje no enviado! ');
            session()->flash('flash_type','danger');
            session()->flash('flash_message','No fue posible enviar el mensaje.');
        }

        return redirect()->back()->withInput();

    }
    

 
    public function addQuestion(Request $request){
        
        /*busco la información del producto que estaba viendo el usuario */
        $producto                   = Product::find($request->product_id);
        /*genero el objeto de tipo pregunta*/
        $question_answer            = new QuestionAnswer;
        
        $question_answer->product_id =   $request->product_id;
        $question_answer->name       =   $request->name;
        $question_answer->email      =   $request->email;
        $question_answer->question   =   $request->question;
        //$question_answer->answer     =   $request->product_id;
        $question_answer->status     =   2;
        $question_answer->created_at =   date("Y-m-d H:i:s");
        $question_answer->updated_at =   date("Y-m-d H:i:s");
        $r  = $question_answer->save();
        if( $r > 0 ){
            session()->flash('flash_title','Éxito');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Pregunta realizada sin problemas');
        }else{
            session()->flash('flash_title','Aviso!');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','Algo salió mal y la pregunta no pudo ser realizada');            
        }
        return redirect( route("product-detail",["id" => $producto->id, "slug" => $producto->slug ]));
    }

    public function addRating(Request $request){
        $review  = ( $request->id > 0 ) ? Review::find($request->id) : new Review ;

        $review->customer_id =   Auth::guard('customer-web')->user()->id; 
        $review->product_id  =   $request->product_id; 
        $review->rating      =   $request->valor; 
        $review->review      =   $request->review; 
        $review->created_at  =   date("Y-m-d H:i:s"); 
        $review->updated_at  =   date("Y-m-d H:i:s"); 
        $r                   =   $review->save();
        
        $producto            =  Product::find($request->product_id);

        if( $r > 0 ){
            session()->flash('flash_title','Éxito');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Se ha realizado la evaluación de este producto');

            $rating           = Product::valoracion($request->product_id);
            $producto->rating = $rating;
            $producto->save();

        }else{
            session()->flash('flash_title','Aviso!');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','Algo salió mal y no pudo ser guardada su evaluación');            
        }
        return redirect( route("product-detail",["id" => $producto->id, "slug" => $producto->slug ]));
    }

    //GCUAMEA
    public function exportOrderPDF(Request $request){
        $data['car'] = Cart::content();
        $data['customer'] = Auth::guard('customer-web')->user();
        $data['subtotal'] = 0;
        $data['shipmentTotal'] = 0;
        
        foreach($data['car'] as $items){
            $data['subtotal'] += ($items->price * $items->qty);
            $data['shipmentTotal'] += $items->options->shipment_cost;
        }
        
        //$order = Order::findOrFail($request->id);
        $pdf = PDF::loadView('website.pdfQuotation', $data);
        $pdf->setPaper('A4', 'portrait');
        //return View::make('admin.order.pdf', ['order' => $order]);
        return $pdf->stream('Cotizacion ');    
    }

    public function addProductOrderProgramming(Request $request, Product $product, $ban = false){
        if(Auth::guard('customer-web')->check()){
            $customer = Auth::guard('customer-web')->user();

            $priceItem = $product->final_price;
            
            $priceListId = Auth::guard('customer-web')->user()->price_list_id;

            if($product->liquidado != 1){
                $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                if($priceListId){
                    $priceList = PricesListProducts::where('price_list_id','=',$priceListId)->where('product_id','=',$product->id)->first();
                    if($priceList->price < $priceItem){
                        $priceItem = $priceList->price;
                    }
                }
            }else{
                $priceItem = $product->liquidado_price;
            }
            

            $newOrder = new OrderProgrammingViews();
            $newOrder->customer_id = $customer->id;
            $newOrder->product_id = $product->id;
            $newOrder->sku = $product->sku;
            $newOrder->name = $product->name;
            $newOrder->category = $product->category_id;
            $newOrder->unit_price = $priceItem;
            $newOrder->quantity = $request->quantity;
            $newOrder->status = 0;
            $newOrder->date_send = date("Y-m-d");
            $newOrder->save();

            $cart = route('programming');
            session()->flash('flash_message','Producto agregado a programación de pedidos. <a href="'.$cart.'">Ir a programación de pedidos</a>');
            return  redirect()->back();
        }else{
            session()->flash('flash_title','Error');
            session()->flash('flash_type','warning');
            session()->flash('flash_message','Recuerde Iniciar Sesion antes de agregar un producto');
            return redirect()->back();
        }
    }

    public function programming(){
        
        $data['orderProg'] = [];
        if(Auth::guard('customer-web')->check()){
            $customer = Auth::guard('customer-web')->user();
            $data['orderProg'] = OrderProgrammingViews::where('customer_id','=',$customer->id)
            ->where('status','=',0)->get();
        }

        $data['total'] = 0;
        foreach($data['orderProg'] as $item){
            
            $data['total'] += $item->quantity * $item->unit_price;
        }
        //dd($data['total']);
        $data['webtitle'] = 'Programacion de Pedido';//GCUAMEA
        $data['cartBasket'] = Cart::content();
        $data['maxQty'] = 10;
        $data['free_shipment'] = Config::select('value')->where('name', 'FREE_SHIPMENT')->first();
        $data['amount'] = Config::select('value')->where('name', 'FREE_SHIPMENT_AMOUNT')->first();
        $data['shoppingcart']=new ShoppingCart();
        return view('website.programming',$data);
    }

    public function deleteProductFromProgramming($id=0){
        $result = OrderProgrammingViews::where('id','=',$id);
        if($result){
            $result->delete();
            
            return redirect()->back();
        }

        session()->flash('flash_title','Error');
        session()->flash('flash_type','warning');
        session()->flash('flash_message','Producto no encontrado en pedido');
        return redirect()->back();
    }

    public function updateProductProgramming(Request $request){
        $orderProgramming = collect($request->all());
       
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
            $orderProgramming_db = OrderProgrammingViews::where('id', $orderProgramming)->first();
            $orderProgramming_db->quantity = $val[0];
            $orderProgramming_db->date_send = date($val[1]);
            $orderProgramming_db->save();  
        }
        session()->flash('flash_type','success');
        session()->flash('flash_message','Pedido Actualizado Correctamente.');
        return redirect()->back();
    }

    public function createOrderProgramming(){
        if(Auth::guard('customer-web')->check()){
            $customer = Auth::guard('customer-web')->user();
            $orderProgrammingView = OrderProgrammingViews::where('customer_id','=',$customer->id)
                ->where('status','=',0)->get();
            $orderProgramming = new OrderProgramming();
            $orderProgramming->customer_id = $customer->id;
            $orderProgramming->name = $customer->name . " " . $customer->lastname;
            $orderProgramming->status = 0;
            $orderProgramming->save();
            $subtotal = 0;
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

                $subtotal += ($item->quantity*$item->unit_price);
            }
            $orderProgramming->subtotal = $subtotal;
            $orderProgramming->total = $subtotal;
            $orderProgramming->save();
            $orderDetails = OrderProgrammingDetails::where('order_programming_id','=',$orderProgramming->id)->get();
            //Send Email
            //dd($orderDetails);
            Mail::to($customer->email)->send(new OrderProgrammingCreated($orderProgramming, $customer));
            $orders_email = Config::where('name', 'EMAIL_ORDERS')->first();
            if($orders_email){
                Mail::to($orders_email->value)->send(new OrderProgrammingCreated($orderProgramming, $customer));
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
    }

    public function concepto(){
        return view('website.concepto');
    }

    public function nosotros(){
        return view('website.nosotros');
    }

    public function politica_precios(){
        return view('website.politica-precios');
    }

    public function ventajas(){
        return view('website.ventajas');
    }

    public function beneficios(){
        return view('website.beneficios');
    }

    public function contacto(){
        return view('website.contacto');
    }

    public function settlementList(Request $request,$slug=null,$params = null,  $child=null){
        $cat_obj = new Category;
        $data['price_customer'] = null;
        $data['webtitle'] = 'Tienda';
        $data['slug'] = $slug;//Para marcar categoria activa
        $data['child'] = $child;
        $data['products'] = Product::liquidacion($request->slug, $child, $params);
        $data['categories'] = Category::where('parent_id',null)->orderBy('order','ASC')->get();//Menu
        $data['categories'] = $cat_obj->categoriesWithProductsAndStock();
        
        $category = Category::where('slug', $slug)->first();
        if(Auth::guard('customer-web')->check() && isset($category->id) && $category->id != null){
            // vió categoria
            $customerCategoryViews = CategoryCustomerViews::updateOrCreate(['category_id' => $category->id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            $customerCategoryViews->views += 1;
            $customerCategoryViews->save();
        }

        //GCUAMEA
        if(Auth::guard('customer-web')->check()){
            if(Auth::guard('customer-web')->user()->price_list_id != 0){
                $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                $data['price_customer'] = PricesListProducts::where('price_list_id','=',$priceListId)->get();
                //dd($data['price_customer']);
            }
        }

        //Guardado de vistas por categoría
        if(isset($category->id) && $category->parent_id != null){
            $categoryViews = CategoryViews::updateOrCreate(['category_id' => $category->id]);
            $categoryViews->views += 1;
            $categoryViews->save();
        }
        //   
        $data['filterOrderBy'] = Product::$filterOrderBy;
        return view('website.settlement-list',$data);
    }

    public function conceptoJAT(){
        return view('website.jat.concepto');
    }

    public function nosotrosJAT(){
        return view('website.jat.nosotros');
    }

    public function politica_preciosJAT(){
        return view('website.jat.politica-precios');
    }

    public function ventajasJAT(){
        return view('website.jat.ventajas');
    }

    public function beneficiosJAT(){
        return view('website.jat.beneficios');
    }

    public function contactoJAT(){
        return view('website.jat.contacto');
    }

    public function settlementListJAT(Request $request,$slug=null,$params = null,  $child=null){
        //dd($request->slug);
        $cat_obj = new Category;
        $data['price_customer'] = null;
        $data['webtitle'] = 'Tienda';
        $data['slug'] = $slug;//Para marcar categoria activa
        $data['child'] = $child;
        $data['products'] = Product::liquidacion($request->slug, $child, $params);
        $data['categories'] = Category::where('parent_id',null)->orderBy('order','ASC')->get();//Menu
        $data['categories'] = $cat_obj->categoriesWithProductsAndStock();
        
        $category = Category::where('slug', $slug)->first();
        if(Auth::guard('customer-web')->check() && isset($category->id) && $category->id != null){
            // vió categoria
            $customerCategoryViews = CategoryCustomerViews::updateOrCreate(['category_id' => $category->id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            $customerCategoryViews->views += 1;
            $customerCategoryViews->save();
        }

        //GCUAMEA
        if(Auth::guard('customer-web')->check()){
            if(Auth::guard('customer-web')->user()->price_list_id != 0){
                $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                $data['price_customer'] = PricesListProducts::where('price_list_id','=',$priceListId)->get();
                //dd($data['price_customer']);
            }
        }

        //Guardado de vistas por categoría
        if(isset($category->id) && $category->parent_id != null){
            $categoryViews = CategoryViews::updateOrCreate(['category_id' => $category->id]);
            $categoryViews->views += 1;
            $categoryViews->save();
        }
        //   
        $data['filterOrderBy'] = Product::$filterOrderBy;
        return view('website.jat.settlement-list',$data);
    }

    public function offersList(Request $request,$slug=null,$params = null,  $child=null){
        //dd($request->slug);
        $cat_obj = new Category;
        $data['price_customer'] = null;
        $data['webtitle'] = 'Tienda';
        $data['slug'] = $slug;//Para marcar categoria activa
        $data['child'] = $child;
        $data['products'] = Product::whereNotNull('offer_price')
                                        ->where('offer_price','>',0)
                                        ->whereNotNull('offer_date_end')
                                        ->where('offer_date_end','>=',date("Y-m-d"))
                                        ->where('liquidado','=',0)->paginate(12);
        $data['categories'] = Category::where('parent_id',null)->orderBy('order','ASC')->get();//Menu
        $data['categories'] = $cat_obj->categoriesWithProductsAndStock();
        
        $category = Category::where('slug', $slug)->first();
        if(Auth::guard('customer-web')->check() && isset($category->id) && $category->id != null){
            // vió categoria
            $customerCategoryViews = CategoryCustomerViews::updateOrCreate(['category_id' => $category->id,
                                                                    'customer_id' => Auth::guard('customer-web')->user()->id]);
            $customerCategoryViews->views += 1;
            $customerCategoryViews->save();
        }

        //GCUAMEA
        if(Auth::guard('customer-web')->check()){
            if(Auth::guard('customer-web')->user()->price_list_id != 0){
                $priceListId = Auth::guard('customer-web')->user()->price_list_id;
                $data['price_customer'] = PricesListProducts::where('price_list_id','=',$priceListId)->get();
                //dd($data['price_customer']);
            }
        }

        //Guardado de vistas por categoría
        if(isset($category->id) && $category->parent_id != null){
            $categoryViews = CategoryViews::updateOrCreate(['category_id' => $category->id]);
            $categoryViews->views += 1;
            $categoryViews->save();
        }
        //   
        $data['filterOrderBy'] = Product::$filterOrderBy;
        return view('website.offerts-list',$data);
    }
}