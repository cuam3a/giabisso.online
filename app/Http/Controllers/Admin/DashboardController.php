<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\ShoppingCart;
use App\Models\ProductViews;
use Carbon\Carbon;

use Auth;

class DashboardController extends Controller
{

	public  $mes = [
        1 => "Ene", 
        2 => "Feb",
        3 => "Mar",
        4 => "Abr",
        5 => "May",
        6 => "Jun",
        7 => "Jul",
        8 => "Ago",
        9 => "Sep",
        10 => "Oct",
        11 => "Nov",
        12 => "Dic"
    ];

	public function __construct(){
		$this->middleware('auth:admin-web');
	}



	//Vista principal del dashboard
    public function dashboard(){
		$data["mas_pedidos"]             = Order::dashBoardMasPedidos();
		//$data["mas_pedidos_pendientes"]  = Order::dashBoardMasPedidosStatus("pendiente");
		$data["mas_pedidos_pagados"]     = Order::dashBoardMasPedidosStatus("pagado");
		$data["total_ordenes"]           = Order::all()->count();
		$data["total_ordenes_pendiente"] = Order::where("payment_status",Order::$payment_text["pendiente"])->count();
		$data["total_ordenes_pagado"]    = Order::where("payment_status",Order::$payment_text["pagado"])->count();
		$data["olvidados"]    			 = ShoppingCart::all()->count();
		$data["graficaOrderVentas"]    	 = $this->prepararDatos( Order::orderVentas() );
		$data["categorias"]    	 		 = Order::soldCategory() ;
		$data["visits"]    	 		     = $this->prepararDatosVisits(Order::visitsMonth()) ;
		$data["vistas"]    	 		 	 = ProductViews::orderBy("views","desc")->take(30)->get();

			//dd($data["vistas"]);

		return view('admin.dashboard',$data);
    }


    public function prepararDatos($datos){
		$return["mes"]    = array();
		$return["orders"] = array();
		$return["ventas"] = array();
	    	foreach ($datos as $dato ) {	
				array_push($return["mes"],(String) $this->mes[$dato->mes] );
				array_push($return["orders"], $dato->order);
				array_push($return["ventas"], $dato->venta);
	    	}
    	return $return;
    }

    public function prepararDatosVisits($datos){
		$return["fecha"]    = array();
		$return["visits"] = array();
	    	foreach ($datos as $dato ) {	
				array_push($return["fecha"],(String) $dato->ano." - ".$this->mes[$dato->mes] );
				array_push($return["visits"], $dato->visits);
	    	}
    	return $return;
    }


}
