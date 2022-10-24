<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ShoppingCart;

class CartRecover extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $cart;

    public function __construct($cart)
    {
        $this->cart = ShoppingCart::where('identifier',$cart->identifier)->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.abandoned_cart',['cart' => $this->cart])->subject('Ya casi es tuyo!!!');
    }
}
