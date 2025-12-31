<?php

namespace App\Mail;

use App\Models\Topup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TopupInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Topup $topup;
    public int $coins;

    public function __construct(Topup $topup, int $coins)
    {
        $this->topup = $topup;
        $this->coins = $coins;
    }

    public function build()
    {
        return $this->subject('Invoice Top Up Coins #' . $this->topup->order_id)
            ->view('emails.topup-invoice');
    }
}
