<?php

namespace App\Library;

use MP;

class MercadoPago 
{
    private static $comision = 0.0379; //Comision del 3.79%
    private static $adicion = 4; //Comision + 4 pesos
    private static $iva = 1.16;

    public static $tipo = [
    	1 => "payment",
    	2 => "merchant_order"
    ];

    public static $tipoTxt = [
    	"PAYMENT" => 1,
    	"MERCHANT_ORDER" => 2
    ];

    public static $status = [
    	1 => "Pendiente",
    	2 => "Pagado",
    	3 => "Autorizado", 			//The payment has been authorized but not captured yet
		4 => "En proceso", 			//Payment is being reviewed
		5 => "En mediación", 		//Users have initiated a dispute
		6 => "Rechazado", 			//Payment was rejected. The user may retry payment.
		7 => "Cancelado", 			//Payment was cancelled by one of the parties or because time for payment has expired
		8 => "Reembolsado", 		//Payment was refunded to the user
		9 => "Recargo de Tarjeta"	//Was made a chargeback in the buyerâ€™s credit card
    ];

    public static $statusTxt = [
    	"pending" 		=> 1,
    	"approved" 		=> 2,
    	"authorized" 	=> 3, //The payment has been authorized but not captured yet
		"in_process" 	=> 4, //Payment is being reviewed
		"in_mediation" 	=> 5, //Users have initiated a dispute
		"rejected"		=> 6, //Payment was rejected. The user may retry payment.
		"cancelled"		=> 7, //Payment was cancelled by one of the parties or because time for payment has expired
		"refunded"		=> 8, //Payment was refunded to the user
		"charged_back"	=> 9  //Was made a chargeback in the buyerâ€™s credit card
    ];

    public static $payment_types = [
        "account_money" => "Crédito en Mercado Pago",
        "ticket" => "Ticket de pago",
        "bank_transfer" => "Transferencia bancaria",
        "atm" => "Cajero automático",
        "credit_card" => "Tarjeta de crédito",
        "debit_card" => "Tarjeta de débito",
        "prepaid_card" => "Tarjeta de prepago"
    ];

    //Retorna el precio con la comisiÃ³n incluida de Mercado Pago
    public static function precioConComision($precio){

    	$total = ($precio + self::$adicion * self::$iva) / 
    			( 1 - self::$comision * self::$iva);

    	return round($total, 2);
    }

     // Retorna botÃ³n de pago a MercadoPago
    public static function botonPago($id, $nombre, $titulo, $precio, $incluirComision = true, $mail){
        $link = self::linkDePago($id, $nombre, $titulo, $precio, $incluirComision, $mail);
        $boton = "<b>Intente nuevamente</b>";

        if($link != ""){
            $boton = '<a name="MP-payButton" mp-mode="redirect" class="green-tr-l-rn-mxall" href="'.$link.'">Pagar</a>               
                <script type="text/javascript">
                (function(){function $MPC_load(){window.$MPC_loaded !== true && (function(){var s = document.createElement("script");s.type = "text/javascript";s.async = true;s.src = document.location.protocol+"//secure.mlstatic.com/mptools/render.js";var x = document.getElementsByTagName("script")[0];x.parentNode.insertBefore(s, x);window.$MPC_loaded = true;})();}window.$MPC_loaded !== true ? (window.attachEvent ?window.attachEvent("onload", $MPC_load) : window.addEventListener("load", $MPC_load, false)) : null;})();
                </script>';
        } 

        return $boton;
    }

    // Retorna el Link de pago a MercadoPago
    public static function linkDePago($id, $nombre, $titulo, $precio, $incluirComision = true, $mail){
        $p = self::_crearPreferencias($id, $nombre, $titulo, $precio, $incluirComision, $mail);

        // print_r($p);
        // exit();
        // echo env('MERCADOPAGO_LINK');
        // echo "<br>";
        // echo env('MP_APP_ID');
        // exit;

        echo
        $link = "";

        if($p != null){
            $link = $p["response"][env('MERCADOPAGO_LINK')];
        }

        return $link;
        
    }

    //Crear preferencia de pago
    private static function _crearPreferencias($id, $nombre, $titulo, $precio, $incluirComision = false, $mail){
        if($incluirComision){
            $precio = self::precioConComision($precio);
        }

        // \Log::info("[MERCADOPAGO] LINK: ".route('payment-success', ['order' => $id, 'email' => $mail]));

        $preference_data = array (
            "items" => array (
                array (
                    "id" => $id,
                    "title" => $titulo,
                    "quantity" => 1,
                    "currency_id" => "MXN",
                    "unit_price" => $precio
                )
            ),
            "back_urls" => array(
                /** CHANGE THIS FOR route() in prod */
                "success" => route('payment-success', ['order' => $id, 'email' => $mail]),
                "pending" => route('payment-processing'),
                "failure" => route('payment-canceled')
            ),
            // "notification_url" => "http://test-pago.ddns.net/pedidos/notificaciones/mp",
            "notification_url" => route('notificaciones-mercado-pago'),
            "payer" => array(
                "name" => $nombre,
                "identification" => array(
                    "type" => "Certificado",
                    "number" => $id
                )
            )
        );
        //dd($preference_data);
        $p = null;
        try{
            $p = MP::create_preference($preference_data);            
        }
        catch(\Exception $e){
            \Log::info("[MERCADOPAGO]: ". json_encode($e->getMessage()) . " ID: " . $id);
        }

        return $p;
    }

    //Revisar la respuestas de Mercado Pago WebHook
    public static function revisarPeticion($type, $id){
    	$respuesta = [
    		"status" => "error",
    		"response" => ""
        ];
        
        \Log::info("[MERCADOPAGO]: revisarPeticion: ".$type);

    	//Revisar tipo "merchant_order"
    	if($type == self::$tipo[2]){
            try{
                $collection = MP::get('/merchant_orders'. '/' . $id);
                if($collection["status"] == 200){
                    $items = $collection["response"]["items"];
                    $cuenta = $items[0]["id"];
                    $cuenta = intval($cuenta);
                    $respuesta["status"] = "success";
                    $respuesta["id_pago"] = $cuenta;
                    $respuesta["response"] = $collection["response"];
                }   
                else{
                    throw new \Exception("Error al recibir la respuesta", 1);
                }
            }
            catch(\Exception $e){
                \Log::info("[MERCADOPAGO]: ". json_encode($e->getMessage()) . " ID: " . $id);
            }
        }
        //Revisar tipo "payment"
        else if($type == self::$tipo[1]){
        	try{
                $collection = MP::get_payment($id);
                if($collection["status"] == 200){
                    $collection = $collection["response"]["collection"];
                    $respuesta["status"] = "success";
                    $respuesta["response"] = $collection;
                    $respuesta["id_orden"] = $collection["merchant_order_id"];
                }   
                else{
                    throw new \Exception("Error al recibir la respuesta", 1);
                }
            }
            catch(\Exception $e){
                \Log::info("[MERCADOPAGO]: ". json_encode($e->getMessage()) . " ID: " . $id);
            }
        }
        else{
            \Log::info("[MERCADOPAGO]: Petición inválida. ID: " . $id);
        }

        return $respuesta;
    }

    public static function crearUsuarioTest(){
        $response = [];
        try{
            $response = MP::get('/users/test_user',['site_id' => 'MLM']);            
        }
        catch(\Exception $e){
            $response = $e;
        }
        return $response;
    }
}