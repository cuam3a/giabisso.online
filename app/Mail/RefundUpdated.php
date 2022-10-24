<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Refunds;


class RefundUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $refund;
    public $instruc;

    public function __construct(Refunds $refund,$instruc)
    {
        $this->refund  = $refund;
        $this->instruc = $instruc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        
        return $this->subject('Devolución '.$this->refund->folio()." ".$this->refund->getRefundStatus($this->refund->status)['text'])->view('emails.refund-status');
    }
}
