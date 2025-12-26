<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function handle(Request $request, string $code)
    {
        /**
         * sementara dummy data
         * nanti diganti Eloquent
         */
        if ($code === '404') {
            return view('redirect.not-found');
        }

        if ($code === 'expired') {
            return view('redirect.expired');
        }

        if ($code === 'pin') {
            return view('redirect.pin', compact('code'));
        }

        // contoh redirect normal
        return view('redirect.countdown', [
            'target' => 'https://google.com',
            'note'   => 'Redirecting...'
        ]);
    }
}
