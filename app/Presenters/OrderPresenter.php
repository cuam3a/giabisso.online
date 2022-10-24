<?php

namespace App\Presenters;

use App\Models\Order;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\View;

class OrderPresenter extends Presenter{

    // Mostrar si hay algun mensaje sin ver
    public function newMessage(){
        $newMessage = 'Visto';
        $class = 'success';

        if($this->model->new_message){
            $newMessage = 'Nuevo mensaje';
            $class = 'danger';
        }

        return new HTMLString('<span class="badge badge-'.$class.'">'.$newMessage.'</span>');
    }

    // Mostrar el listado de mensajes
    public function showMessages($from){
        $messages = '';
        $date = \Carbon\Carbon::yesterday()->startOfDay();

        if(!$this->model->messages->count()){
            $messages = '<div class="text-center">Aún no hay mensajes</div>';
        }

        foreach($this->model->messages as $message){
            $showDate = false;
            // Comparar si las fechas son iguales para no mostrar la siguiente fecha
            if($message->created_at->startOfDay()->diffInDays($date)){
                $showDate = true;
            }
            // Mostar mensaje
            $messages .= $message->present()->messageBox($from, $showDate);
            
            // Obtener la fecha del mensaje para compararla con el siguiente
            $date = $message->created_at->startOfDay();
        }

        return $messages;
    }

    public function getCustomerName(){
        if($this->model->customer){
            $customerName = $this->model->customer->fullname();
        }
        else{
            $customerName = $this->model->fullname() . " (Sin registro)";
        }
        return $customerName;
    }
}