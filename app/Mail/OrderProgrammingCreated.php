<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderProgrammingDetails;
use App\Models\OrderProgramming;
use App\Models\Customer;

class OrderProgrammingCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $order;
    public $orderDetails;
    public $customer;

    public function __construct(OrderProgramming $order, Customer $customer)
    {
        $this->order = $order;
        $this->orderDetails = OrderProgrammingDetails::where('order_programming_id','=',$order->id)->get();
        $this->customer = $customer;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pedido Programado '.$this->order->folio()." Nuevo pedido")->view('emails.new-order-programming');
    }
}
