<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ShortlinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'destination_url' => 'required|url'
        ], [
            'destination_url.url' => 'Masukkan URL yang valid',
        ]);

        // ===== Ambil TITLE dari URL =====
        $pageTitle = null;

        try {
            $response = Http::timeout(5)->get($request->destination_url);

            if ($response->ok()) {
                preg_match('/<title>(.*?)<\/title>/is', $response->body(), $matches);
                $pageTitle = $matches[1] ?? null;
                $pageTitle = $pageTitle ? trim(html_entity_decode($pageTitle)) : null;
            }
        } catch (\Exception $e) {
            $pageTitle = null; // gagal ambil title → tetap lanjut
        }

        // ===== Generate short code =====
        do {
            $shortCode = Str::random(6);
        } while (ShortLink::where('short_code', $shortCode)->exists());

        // ===== Simpan =====
        $short = ShortLink::create([
            'destination_type' => 'url',
            'destination_url'  => $request->destination_url,
            'short_code'       => $shortCode,
            'title'            => $pageTitle,
            'note'             => $pageTitle,
            'user_id'          => auth()->check() ? auth()->id() : null,
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $short->short_code,
            'destination' => $short->destination_url,
            'short_url'   => url($short->short_code),
            'title'       => $pageTitle,
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
        // Pastikan modal terbuka jika error
        session()->flash('open_result_modal', true);

        // Ambil link berdasarkan old_code
        $link = ShortLink::where('short_code', $request->old_code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'old_code' => [
                'required',
                'string',
                'exists:short_links,short_code',
            ],

            'short_code' => [
                'required',
                'string',
                'min:5',
                'max:50',
                'alpha_dash',
                function ($attr, $value, $fail) use ($link) {

                    // Jika tidak berubah, lewati cek
                    if ($value === $link->short_code) {
                        return;
                    }

                    $exists = ShortLink::where(function ($q) use ($value) {
                            $q->where('short_code', $value)
                            ->orWhere('custom_alias', $value);
                        })
                        ->where('id', '!=', $link->id)
                        ->exists();

                    if ($exists) {
                        $fail('Short code sudah digunakan.');
                    }
                },
            ],
        ], [
            'short_code.required'   => 'Short code wajib diisi.',
            'short_code.alpha_dash' => 'Hanya boleh huruf, angka, dash (-) dan underscore (_).',
            'short_code.max'        => 'Maksimal 50 karakter.',
            'short_code.min'        => 'Minimal 5 karakter.',
        ]);

        // Update
        $link->update([
            'short_code' => $validated['short_code'],
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $link->short_code,
            'destination' => $link->destination_type === 'url'
                                ? $link->destination_url
                                : 'FILE',
            'short_url'   => url($link->short_code),
        ]);
    }

}
