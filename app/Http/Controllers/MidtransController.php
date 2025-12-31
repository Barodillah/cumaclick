<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topup;
use App\Models\Wallet;
use Midtrans\Notification;
use Illuminate\Support\Facades\Mail;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $notification = new Notification();

        $transaction = $notification->transaction_status;
        $type        = $notification->payment_type;
        $orderId     = $notification->order_id;
        $fraud       = $notification->fraud_status;

        $topup = Topup::where('order_id', $orderId)->first();
        if (!$topup) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $walletCredited = false;

        switch ($transaction) {
            case 'capture':
                if ($type === 'credit_card' && $fraud === 'challenge') {
                    $topup->transaction_status = 'pending';
                } else {
                    $topup->transaction_status = 'success';
                    $walletCredited = $this->creditWallet($topup);
                }
                break;

            case 'settlement':
                $topup->transaction_status = 'success';
                $walletCredited = $this->creditWallet($topup);
                break;

            case 'pending':
            case 'authorize': // authorize bisa dianggap pending
                $topup->transaction_status = 'pending';
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failure':
                $topup->transaction_status = 'failed';
                break;

            case 'refund':
            case 'partial_refund':
                $topup->transaction_status = 'refunded';
                // jika ingin, bisa debit wallet disini
                break;
        }

        // Simpan info tambahan Midtrans
        $topup->transaction_id = $notification->transaction_id ?? null;
        $topup->payment_type   = $type;
        $topup->fraud_status   = $fraud;
        $topup->payload        = $notification;
        $topup->save();

        // Kirim email jika topup berhasil
        if ($walletCredited) {
            Mail::send('emails.topup-success', ['topup' => $topup], function ($message) use ($topup) {
                $message->to($topup->user->email, $topup->user->name)
                        ->subject('Topup Berhasil!');
            });
        }

        // Webhook harus selalu mengembalikan 200 OK
        return response()->json(['message' => 'OK'], 200);
    }


    private function creditWallet($topup)
    {
        $userWallet = Wallet::firstOrCreate(['user_id' => $topup->user_id]);
        $userWallet->balance += $topup->coins;
        $userWallet->save();

        $userWallet->transactions()->create([
            'type' => 'credit',
            'amount' => $topup->coins,
            'source' => 'top_up',
            'related_type' => 'topups',
            'related_id' => $topup->id,
            'description' => 'Topup via Midtrans Order ID: ' . $topup->order_id,
        ]);

        return true;
    }
}


