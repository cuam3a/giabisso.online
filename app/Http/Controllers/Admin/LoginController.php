<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use App\Mail\Recover;
use App\Mail\RecoverAdmin;
use App\Mail\PasswordChangedAdmin;
use Validator;
use Redirect;
use Auth;
use App\Models\Admin;

class LoginController extends Controller
{
    public function __construct()
    {   
        $this->middleware('guest:admin-web',['except' => 'logoutAdmin']);
    }

    //Show Login view
    public function loginAdmin(){      
    	return view('admin.login');
    }

    //Login to Backoffice
    public function signInAdmin(Request $request)
    {
        \Log::info("[signInAdmin]: ". json_encode($request->all()));
        $email = $request->email;
        $password = $request->password;
        $remember=false;
        if (Auth::guard('admin-web')->attempt(['email' => $email, 'password' => $password])) {
            return response()->json(['status' => true, 'message' => 'Listo']);
        }
        return response()->json(['status' => false, 'message' => 'Correo y contraseña incorrectos. Intente de nuevo.']);
    }

    //Logout
    public function logoutAdmin(){
        Auth::guard('admin-web')->logout();
        return Redirect::route('login-admin');
    }

    // Send mail for recovering password
    function sendMailForgetPassword(Request $request){
        
        $data = [
            'email' => 'required|email',
        ];

        // reCaptcha field for validator
        if(env('APP_ENV') != "dev"){
            $data['g-recaptcha-response'] = 'required|recaptcha';
        }
        
        $validator = Validator::make($request->all(),$data);        
        
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }
        
        $user = Admin::where('email',$request->email)->first();

        if( $user ){
            $token = bin2hex(random_bytes(50));
            $user->reset_token = $token;
            $user->reset_try = 0;

            //Envio de correo de recuperación
            $error = null;
            try{
                Mail::to($user->email)
                    ->send(new RecoverAdmin($user));                
            }catch(\Exception $e){
                $error = $e->getMessage();
                \Log::info("[PasswordRecover]: ".$error);
                return response()->json(['status' => true, 'message' => 'Ocurrió un error, intenta nuevamente.']);
            }

            $user->save();

        }

        return response()->json(['status' => true, 'message' => 'Se envío un correo con las instrucciones']);

    }

    public function recoverPassword($email, $token, Request $request){
        $admin = Admin::where('email',$email)->first();
        $valid = true;

        if($admin){                     
            if($admin->reset_token != "" && $admin->reset_token == $token){
                // Post request
                if($request->isMethod('post')){
                    if($request->input('password') == $request->input('repassword')){
                        $newPassword = bcrypt($request->input('password'));
                        $admin->password = $newPassword;
                        $admin->reset_token = "";
                        $admin->reset_try = 0;
                        $admin->save();
                         try{
                            Mail::to($admin->email)->send(new PasswordChangedAdmin($admin));                
                        }catch(\Exception $e){
                            $error = $e->getMessage();
                            \Log::info("[PasswordRecoverUpdated]: ".$error);
                        }
                        session()->flash('flash_title','Recuperar contraseña');
                        session()->flash('flash_type','success');
                        session()->flash('flash_message_login','Contraseña cambiada correctamente');
                        return Redirect::route('login-admin');
                    }
                }
            }
            else{  
                $valid = false;
                $admin->reset_try++;
                if($admin->reset_try == 5){
                    $admin->reset_token = "";
                    $admin->reset_try = 0;
                }
                $admin->save();
            }
        }
        else{
            $valid = false;
        }

        return view('admin.reset-password', [
        'title' => 'Cambiar contraseña',
        'user' => $admin,
        'valid' => $valid
        ]);
    }
}