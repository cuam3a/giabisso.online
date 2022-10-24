<?php

namespace App\Console\Commands;
use App\Models\ShoppingCart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\CartRecover;
use App\Library\Carrito;

class AbandonedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:abandonedcarts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia correos a carritos que no terminaron la compra.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        $carts = ShoppingCart::abandonedCarts();
        foreach ($carts as $cart) {
           Mail::to($cart->identifier)->send(new CartRecover($cart));
        }
        \Log::info("[Envio de carritos abandonados] Total:".count($carts) );
    }
}
