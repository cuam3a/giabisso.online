<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Order;

class OrderUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Order sent
        if($this->order->status == 2){
            return $this->subject('Pedido '.$this->order->folio()." actualizado")->view('emails.order-sent');
        }
        return $this->subject('Pedido '.$this->order->folio()." actualizado")->view('emails.order-status');
    }
}
