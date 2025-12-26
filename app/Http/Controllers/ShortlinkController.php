<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShortlinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'destination_url' => 'required|url'
        ], [
            'destination_url.url' => 'Masukkan URL yang valid',
        ]);

        do {
            $shortCode = Str::random(6);
        } while (ShortLink::where('short_code', $shortCode)->exists());

        $short = ShortLink::create([
            'destination_type' => 'url',
            'destination_url'  => $request->destination_url,
            'short_code'       => $shortCode,
            'user_id'          => auth()->check() ? auth()->id() : null, // tambahkan user_id jika login
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $short->short_code,
            'destination' => $short->destination_url,
            'short_url'   => url($short->short_code),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120'
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();

        // simpan file di storage private
        $path = $uploadedFile->store('uploads', 'private');

        // generate short code unik
        do {
            $shortCode = Str::random(6);
        } while (ShortLink::where('short_code', $shortCode)->exists());

        // simpan ke database
        $short = ShortLink::create([
            'destination_type' => 'file',
            'destination_url'  => $path,
            'short_code'       => $shortCode,
            'title'            => $originalName,
            'note'             => $originalName,
            'user_id'          => auth()->check() ? auth()->id() : null, // tambahkan user_id jika login
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $short->short_code,
            'destination' => $originalName,
            'short_url'   => url($short->short_code),
        ]);
    }

    public function update(Request $request)
    {
        // pastikan modal terbuka jika ada error
        session()->flash('open_result_modal', true);

        $validated = $request->validate([
            'old_code'   => 'required|exists:short_links,short_code',
            'short_code' => 'required|alpha_dash|min:3|max:50|unique:short_links,short_code',
        ], [
            'short_code.alpha_dash' => 'Hanya boleh huruf, angka, dash (-) dan underscore (_)',
            'short_code.unique'     => 'Short code sudah digunakan, gunakan yang lain!',
        ]);

        $short = ShortLink::where('short_code', $validated['old_code'])->firstOrFail();

        $short->update([
            'short_code' => $validated['short_code'],
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $short->short_code,
            'destination' => $short->destination_type === 'url'
                                ? $short->destination_url
                                : 'FILE',
            'short_url'   => url($short->short_code),
        ]);
    }

}
