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
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $orderId = 'TOPUP-' . auth()->id() . '-' . now()->timestamp;

        $topup = Topup::create([
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'gross_amount' => $request->nominal,
            'coins' => $request->coins,
            'transaction_status' => 'pending',
            'address' => $request->address,   // tambahkan kolom jika perlu
            'phone' => $request->phone,       // tambahkan kolom jika perlu
        ]);

        Mail::to(auth()->user()->email)
            ->send(new TopupInvoiceMail($topup, $request->coins));

        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $topup->gross_amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => $request->phone,           // kirim ke Snap
                'billing_address' => [
                    'address' => $request->address,  // kirim ke Snap
                ],
            ],
        ]);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function topupSuccess(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'coins'       => 'required|numeric|min:1',
            'redirectUrl' => 'required|string'
        ]);

        // Bisa juga simpan transaksi jika perlu
        // WalletTransaction::create([
        //     'user_id' => auth()->id(),
        //     'type'    => 'credit',
        //     'amount'  => $data['coins'],
        //     'source'  => 'midtrans_topup',
        //     'status'  => 'success'
        // ]);

        return view('pages.topup-success', [
            'coins'       => $data['coins'],
            'redirectUrl' => $data['redirectUrl']
        ]);
    }
}
