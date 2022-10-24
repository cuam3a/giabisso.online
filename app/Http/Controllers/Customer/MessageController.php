<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Message;
use App\Models\Order;
use App\Models\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\YouHaveAMessageNew;


class MessageController extends Controller
{
    public function __construct(){
        $this->middleware('auth:customer-web');
    }

     // Enviar un mensaje al administrador sobre su pedido
     public function sendMessage(Order $order, Request $request){


        if(trim($request->new_message) == ""){
            return redirect()->back()->withErrors([
                "message" => "El mensaje es requerido"
            ]);
        }
        $message = new Message();
        $message->order()->associate($order);
        $message->message = $request->new_message;
        $message->from = Message::$from['CUSTOMER'];
        $message->save();

        // Notificar de nuevo mensaje en la orden
        $order->new_message          = 1;
        $order->new_message_customer = 0;
        $order->save();

        $contact = Config::where("name","EMAIL_CONTACT")->first();

        Mail::to($contact->value)->send(new YouHaveAMessageNew($order,0));
        //dd($order);

        return redirect()->back()->with([
            "message" => "Mensaje enviado"
        ]);
    }
}
