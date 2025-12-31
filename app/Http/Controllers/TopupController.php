<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use App\Mail\TopupInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Midtrans\Snap;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|integer|min:5000',
            'coins' => 'required|integer|min:1',
        ]);

        $orderId = 'TOPUP-' . auth()->id() . '-' . now()->timestamp;

        $topup = Topup::create([
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'gross_amount' => $request->nominal,
            'transaction_status' => 'pending',
        ]);

        // ✅ KIRIM EMAIL INVOICE
        Mail::to(auth()->user()->email)
            ->send(new TopupInvoiceMail($topup, $request->coins));

        // ✅ MIDTRANS SNAP
        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $topup->gross_amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ]);

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }
}
