<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Order;

class YouHaveAMessageNew extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order;
    public $i;

    public function __construct(Order $order, $i){
        $this->order = $order;
        $this->i = $i;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->subject("Tiene un nuevo mensaje (pedido ".$this->order->folio()." )")->view("emails.new-message");
    }
}
