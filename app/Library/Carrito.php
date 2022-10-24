<?php

namespace App\Library;
use Auth;
use Cart;
use \Session;
use App\Models\Customer;
use App\Models\ShoppingCart;

class Carrito
{
    /**
     * Store an the current instance of the cart.
     *
     * @param mixed $identifier
     * @return void
     */


    public static function guardar(Customer $customer)
    {   
        if (Auth::guard('customer-web')->check()) {  
            if(Cart::count()>0){
                ShoppingCart::where('identifier',$customer->email)->delete();
                Cart::store($customer->email);
                $carrito = ShoppingCart::where('identifier',$customer->email)->first();
                $carrito->customer()->associate($customer);
                $carrito->total = floatval(Session::get('total'));
                $carrito->created_at = \Carbon\Carbon::now();
                $carrito->save();
            }else{
                if(Auth::guard('customer-web')->user()->shoppingCart != null){
                    Auth::guard('customer-web')->user()->shoppingCart->delete();
                    Cart::destroy();
                }
            }
        }
    }
}