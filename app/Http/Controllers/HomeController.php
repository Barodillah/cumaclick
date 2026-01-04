<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'required|email',
            'pesan' => 'required|string|max:1000',
        ]);

        /** =========================
         *  EMAIL KE ADMIN
         *  ========================= */
        Mail::send('emails.contact', [
            'nama'  => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan,
        ], function ($message) use ($request) {
            $message->to('barodabdillah313@gmail.com')
                    ->subject('Pesan Kontak Baru')
                    ->replyTo($request->email, $request->nama);
        });

        /** =========================
         *  EMAIL BALASAN KE USER
         *  ========================= */
        Mail::send('emails.contact-thanks', [
            'nama' => $request->nama,
        ], function ($message) use ($request) {
            $message->to($request->email, $request->nama)
                    ->subject('Terima Kasih Telah Menghubungi Kami');
        });

        return redirect()
            ->route('home')
            ->with('success', 'Pesan berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}

