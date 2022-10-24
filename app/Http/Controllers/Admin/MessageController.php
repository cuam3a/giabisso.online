<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\YouHaveAMessageNew;



class MessageController extends Controller
{
    public function __construct(){
		$this->middleware('auth:admin-web');
    }
    
    // Lista de los mensajes enviados y recibidos
    public function messages(){

        $messages = Message::grouped()->get();

        return view('admin.message.list', [
            'messages' => $messages
        ]);
    }   

    // Muestra los mensajes dentro de un pedido
    public function messagesOrder(Order $order){
        // Marcar mensaje nuevo como visto
        $order->new_message = 0;
        $order->save();

        return view('admin.message.order-messages',[
            'order' => $order,
            'from' => Message::$from['ADMIN']
        ]);
    }

    // Enviar un mensaje al cliente sobre su pedido
    public function sendMessage(Order $order, Request $request){

        //dd($order);

        if(trim($request->new_message) == ""){
            return redirect()->back()->withErrors([
                "message" => "El mensaje es requerido"
            ]);
        }
        $message = new Message();
        $message->order()->associate($order);
        $message->message = $request->new_message;
        $message->from = Message::$from['ADMIN'];
        $message->save();

        $order->new_message_customer = 1;
        $order->save();

        
        /*aqui se hará el envio*/
        Mail::to($order->email)->send(new YouHaveAMessageNew($order,1));

        return redirect()->back()->with([
            "message" => "Mensaje enviado"
        ]);
    }

}
