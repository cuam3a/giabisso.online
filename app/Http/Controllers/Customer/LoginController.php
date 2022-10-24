<?php

namespace App\Http\Controllers\Customer;

use App\Models\Customer;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use App\Mail\Recover;
use App\Mail\PasswordChanged;
use App\Mail\Welcome;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Library\Carrito;
use App\Models\Config;
use Validator;
use Redirect;
use Auth;
use Hash;
use Cart;

class LoginController extends Controller
{
    public function __construct()
    {   
        $this->middleware('guest:customer-web',['except' => 'logoutCustomer']);
    }
    public function login(Request $request){
        if($request->redirect!=''){ $data['page_origin']=$request->redirect; }else{ $data['page_origin']=''; }
        $data['webtitle'] = 'Mi Cuenta';
        $data['states'] = State::pluck('name','id');
        return view('website.login',$data);
    }

    public function loginJAT(Request $request){
        if($request->redirect!=''){ $data['page_origin']=$request->redirect; }else{ $data['page_origin']=''; }
        $data['webtitle'] = 'Mi Cuenta';
        $data['states'] = State::pluck('name','id');
        return view('website.jat.login',$data);
    }
    
    //Login to Mi Cuenta
    public function signInCustomer(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $remember=false;
        if (Auth::guard('customer-web')->attempt(['email' => $email, 'password' => $password])) {
            if(Cart::count() > 0){
                Carrito::guardar(Auth::guard('customer-web')->user());
            }
            if($request->originurl!='')
                return redirect($request->originurl);
                
            else
                return redirect()->route('website-home');
        }

        session()->flash('flash_title','Correo y/o contraseña incorrectos');
        session()->flash('flash_type','danger');
        session()->flash('flash_message_login','Intente de nuevo');
        return redirect()->back();
    }

    //Login to Mi Cuenta
    public function signInCustomerJAT(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $remember=false;
        if (Auth::guard('customer-web')->attempt(['email' => $email, 'password' => $password])) {
            if(Cart::count() > 0){
                Carrito::guardar(Auth::guard('customer-web')->user());
            }
            if($request->originurl!='')
                return redirect($request->originurl);
                
            else
                return redirect()->route('website-home-jat');
        }

        session()->flash('flash_title','Correo y/o contraseña incorrectos');
        session()->flash('flash_type','danger');
        session()->flash('flash_message_login','Intente de nuevo');
        return redirect()->back();
    }

    public function newCustomer(Request $request){
        
        $customer = new Customer();
        $exist = Customer::where('email',$request->email)->first();
        $originurl='';
        if($request->originurl!=''){
            $originurl=$request->originurl;
        }

       
        if(!$exist){
            { //validaciones
                $data = [
                    'name' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|email|unique:customer,email',
                    'password' => 'required',
                    'password_confirm' => 'same:password',
                    'f_zipcode' => 'required|numeric|digits:5',
                    'f_name' => 'required',
                    'f_rfc' => 'required',
                    'f_address' => 'required',
                    //'f_email' => 'required_if:factura_requiere,on|max:60',
                    //'f_phone' => 'required_if:factura_requiere,on|max:30',
                    'f_city' => 'required',
                    'f_state' => 'required',
                ];
                
                //if(env('APP_ENV') != "dev"){
                //    $data['g-recaptcha-response'] = 'required|recaptcha';
                //}
                //$validator = Validator::make($request->all(),$data,['g-recaptcha-response.required' => 'Captcha requerido']);
                $validator = Validator::make($request->all(),$data);        
                $validator->validate();
                //dd($validator);
                if ($validator->fails()) {
                   return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
            }
            
            //GCUAMEA Credito
            $credit_db = Config::where('name', 'CREDIT')->select('value')->first();
            $credit_days_db = Config::where('name', 'CREDIT_DAYS')->select('value')->first();

            if(empty($credit_db)){
                $credit = 0;
            }else{
                $credit = $credit_db->value;
            }

            if(empty($credit_days_db)){
                $credit_days = 0;
            }else{
                $credit_days = $credit_days_db->value;
            }

            $customer->name = $request->name;
            $customer->lastname = $request->lastname;
            $customer->phone = $request->phone;
            $customer->cell_phone = $request->cell_phone;
            $customer->email = $request->email;
            $customer->password = Hash::make($request->password);
            $customer->f_name = $request->f_name;
            $customer->f_rfc = $request->f_rfc;
            $customer->f_address = $request->f_address;
            $customer->f_zipcode = $request->f_zipcode;
            $customer->f_state = $request->f_state;
            $customer->f_city = $request->f_city;
            $customer->credit = $credit;
            $customer->credit_days = $credit_days;
            $customer->save();

            try{
                Mail::to($customer->email)->send(new Welcome($customer));                
            }catch(\Exception $e){
                $error = $e->getMessage();
                \Log::info("[WelcomeEmail]: ".$error);
            }

            if (Auth::guard('customer-web')->attempt(['email' => $customer->email, 'password' => $request->password])) {
                session()->flash('flash_title','Bienvenido! ');
                session()->flash('flash_type','success');
                //session()->flash('flash_message','Consulta nuestros productos y promociones <a href="{{route("website-home")}}">Ver productos</a>');
                session()->flash('flash_message','Consulta nuestros productos y promociones <a href="/tienda">Ver productos</a>');

                if($originurl!=''){
                    //return redirect()->route($originurl);
                    return redirect($originurl);
                }else{
                    return redirect()->route('my-account');
                }
            }
        }else{
            session()->flash('flash_title','Correo electrónico en uso');
            session()->flash('flash_type','danger');
            session()->flash('flash_message_new_customer','Intente otro correo electrónico');
        }
        return redirect()->back()->withInput();
    }

    //Logout
    public function logoutCustomer(){
        Auth::guard('customer-web')->logout();
        return Redirect::route('website-login');
    }

    public function showForgotPasswordForm()
    {
        return view('website.forgot-password');
    }

    //Recuperar contraseña, por cuenta o por correo
    public function sendResetLinkEmail(Request $request){
        { //validaciones
            $data = [
                'email' => 'required|email',
            ];
            if(env('APP_ENV') != "dev"){
                $data['g-recaptcha-response'] = 'required|recaptcha';
            }
            $validator = Validator::make($request->all(),$data);        
            //$validator->validate();
            
            if ($validator->fails()) {
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }
        
        $customer = Customer::where('email',$request->email)->first();
        //Recuperar contraseña
        if($customer){
            $token = bin2hex(random_bytes(50));
            $customer->recover_token = $token;
            $customer->recover_try = 0;

            //Envio de correo de recuperación
            $error = null;
            try{
                Mail::to($customer->email)->send(new Recover($customer));                
            }catch(\Exception $e){
                $error = $e->getMessage();
                \Log::info("[PasswordRecover]: ".$error);
            }

            if($error){
                session()->flash('flash_title','Recuperar contraseña');
                session()->flash('flash_type','danger');
                session()->flash('flash_message','Ocurrió un error al enviar tu correo, intenta nuevamente');
                return redirect()->back();
            }

            $customer->save();
            session()->flash('flash_title','Recuperar contraseña');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Se te envió un correo para recuperar tu contraseña');
            return redirect()->back();
        }
        else{
            session()->flash('flash_title','Recuperar contraseña');
            session()->flash('flash_type','danger');
            session()->flash('flash_message','Correo electrónico no encontrado <a href="'.route('my-account').'" style="color:#721c24!important;">Crear cuenta</a>');
            return redirect()->back();
        }
    }

    //Recibe el token y verifica si es correcto, para el cambio de password
    public function recoverPassword($email, $token, Request $request){
        $customer = Customer::where('email',$email)->first();
        $valid = true;
        if($customer){                     
            if($customer->recover_token != "" && $customer->recover_token == $token){
                //Cambiar contraseña
                if($request->isMethod('post')){
                    if($request->input('password') == $request->input('repassword')){
                        $newPassword = bcrypt($request->input('password'));
                        $customer->password = $newPassword;
                        $customer->recover_token = "";
                        $customer->recover_try = 0;
                        $customer->save();
                         try{
                            Mail::to($customer->email)->send(new PasswordChanged($customer));                
                        }catch(\Exception $e){
                            $error = $e->getMessage();
                            \Log::info("[PasswordRecoverUpdated]: ".$error);
                        }
                        session()->flash('flash_title','Recuperar contraseña');
                        session()->flash('flash_type','success');
                        session()->flash('flash_message_login','Contraseña cambiada correctamente');
                        return Redirect::route('website-login');
                    }
                }
            }
            else{  
                $valid = false;
                $customer->recover_try++;
                if($customer->recover_try == 5){
                    $customer->recover_token = "";
                    $customer->recover_try = 0;
                }
                $customer->save();
            }
        }
        else{
            $valid = false;
        }

        return view('website.reset-password', [
        'title' => 'Cambiar contraseña',
        'user' => $customer,
        'valid' => $valid
        ]);
    }
}