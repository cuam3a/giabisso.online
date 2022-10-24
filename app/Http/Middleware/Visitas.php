<?php

namespace App\Http\Middleware;
use App\Models\Visit;
use Closure;

class Visitas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        $cuenta = Visit::where("date",date("Y-m-d"))->count();
        $visita = ( $cuenta == 0 ) ? New Visit : Visit::where("date",date("Y-m-d"))->first() ;

        $visita->date   = date("Y-m-d");
        $visita->visits = ( $cuenta == 0 ) ? $cuenta+1 : $visita->visits+1 ;
        $visita->save();


        return $next($request);
    }
}
