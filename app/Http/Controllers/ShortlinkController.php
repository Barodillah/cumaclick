<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShortlinkController extends Controller
{
    /**
     * Proses shorten URL
     */
    public function store(Request $request)
    {
        // sementara dummy dulu (biar flow hidup)
        $short = 'abc123';

        return redirect('/')
            ->withFragment('result')
            ->with([
                'short' => $short,
                'url'   => $request->url
            ]);
    }

    /**
     * Update custom short code
     */
    public function update(Request $request)
    {
        // nanti isi logic update dari process.php
        return back();
    }

    /**
     * Upload file + shorten
     */
    public function upload(Request $request)
    {
        // nanti pindahkan logic upload dari process.php
        return back();
    }

    /**
     * Redirect shortlink
     */
    public function redirect(string $code)
    {
        // nanti ambil dari database
        // sementara dummy
        return redirect()->away('https://google.com');
    }
}

