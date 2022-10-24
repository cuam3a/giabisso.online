<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

        public function resetLinkPassword(){
        $data = Input::all();
        { //validaciones
            $validator = Validator::make($data, [
                'email' => 'required|email'
            ]);
            $validator->validate();
        }
        $customer = Customer::where('email',Input::get('email'));

        if($customer){
            session()->flash('flash_title','Correo correcto');
            session()->flash('flash_type','success');
            session()->flash('flash_message','Bien');
            return redirect()->back();
        }        
        session()->flash('flash_title','Correo y/o contraseña incorrectos');
        session()->flash('flash_type','danger');
        session()->flash('flash_message','Intente de nuevo');
        return redirect()->back();
    }
}
