<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cart;
use App\Library\Calculate;
use App\Library\Carrito;
use App\Models\ShoppingCart;

class RestoreCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed+
     * 
     * 
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('customer-web')->check()) {  
            Cart::destroy(); 
            Cart::restore(Auth::guard('customer-web')->user()->email); 
            Carrito::guardar(Auth::guard('customer-web')->user());
        }
        Calculate::calculateShipping();
        Calculate::calculateTotal();
        return $next($request);
    }
}
