<?php

namespace App\Presenters;

use App\Models\Message;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\View;

class MessagePresenter extends Presenter{

    public function messageBox($from, $showDate){

        $class = '';
        $name = 'Home Express Center';

        // El mensaje es del mismo usuario logueado?
        if($from == $this->model->from){
            $class = 'me';
        }

        // Obtener el nombre del cliente
        if($this->model->from == Message::$from['CUSTOMER']){
            $name = $this->model->order->customer->name;
        }

        return View::make('presenters.message-box', [
            'message' => $this->model,
            'class' => $class,
            'showDate' => $showDate,
            'name' => $name
        ]);
    }
    
}