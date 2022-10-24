<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\RequestProduct;
use App\Models\Customer;

class RequestProd extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $requestProduct;
    public $customer;

    public function __construct(RequestProduct $requestProduct, Customer $customer)
    {
        $this->requestProduct = $requestProduct;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.request-product',['product' => $this->requestProduct, 'user' => $this->customer])->subject('Solicitud de Producto');
    }
}
