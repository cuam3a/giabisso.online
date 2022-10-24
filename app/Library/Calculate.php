<?php

namespace App\Library;

use Cart;
use \Session;
use App\Models\Config;
use App\Models\ShipmentTypes;
class Calculate
{
    public static function calculateShipping(){
        
        $preference_shipment_id = 1;

        $cart_basket = Cart::content();
        $total_type_shipment = array (1 => 0, 2 => 0);
        $total_shipment = 0;

        // echo "<pre>";
        // print_r($cart_basket);
        // exit();

        foreach($cart_basket as $item){
            $total_type_shipment[$item->options->shipment_type] = $total_type_shipment[$item->options->shipment_type] + ($item->options->shipment_cost * $item->qty);
        }

        if($total_type_shipment[$preference_shipment_id] > 0){
            $total_shipment = $total_type_shipment[$preference_shipment_id];
            Session::put('shipping_description', ShipmentTypes::where('name', 'terrestre')->first()->description);
            Session::put('shipment_id', 1);
        }
        else{
            //aereo
            $total_shipment = $total_type_shipment[2];
            Session::put('shipping_description', ShipmentTypes::where('name', 'aereo')->first()->description);
            Session::put('shipment_id', 2);
        }


        $free_shipment_amount = 0;
        if(Config::select('value')->where('name', 'FREE_SHIPMENT_AMOUNT')->first())
            $free_shipment_amount = Config::select('value')->where('name', 'FREE_SHIPMENT_AMOUNT')->first()->value;
        
        $free_shipment = 0;
        if(Config::select('value')->where('name', 'FREE_SHIPMENT')->first())
            $free_shipment = Config::select('value')->where('name', 'FREE_SHIPMENT')->first()->value;

        $flat_rate = 0;
        if(Config::select('value')->where('name', 'FLAT_RATE')->first())
            $flat_rate = Config::select('value')->where('name', 'FLAT_RATE')->first()->value;

        // echo "subtotal:".floatval(Cart::subtotal(2,null,''));
        // echo "ammount:".floatval($free_shipment_amount);
        // echo "free".$free_shipment;

        if($free_shipment == "ON" && floatval(Cart::subtotal(2,null,'')) >= floatval($free_shipment_amount)){
            Session::put('shipping', 0); 
        }
        else{
            Session::put('shipping', $total_shipment);  
        }
    }

    public static function calculateTotal(){
        $total = floatval(Cart::subtotal(2,null,'')) + floatval(Session::get('shipping'));
        Session::put('total', $total);
    }
}