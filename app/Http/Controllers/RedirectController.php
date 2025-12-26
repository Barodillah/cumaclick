<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function handle(Request $request, string $code)
    {
        // 1. Cari berdasarkan short_code atau custom_alias
        $shortLink = ShortLink::where('short_code', $code)
            ->orWhere('custom_alias', $code)
            ->first();

        // 2. Tidak ditemukan
        if (!$shortLink) {
            return view('redirect.not-found');
        }

        // 3. Status tidak aktif / diblokir
        if (!$shortLink->is_active || $shortLink->blocked_at) {
            return view('redirect.not-found');
        }

        // 4. Expired
        if ($shortLink->expired_at && now()->gt($shortLink->expired_at)) {
            return view('redirect.expired');
        }

        // 5. Jadwal aktif
        if ($shortLink->active_from && now()->lt($shortLink->active_from)) {
            return view('redirect.not-found');
        }

        if ($shortLink->active_until && now()->gt($shortLink->active_until)) {
            return view('redirect.expired');
        }

        // 6. Limit klik
        if (
            $shortLink->max_click !== null &&
            $shortLink->click_count >= $shortLink->max_click
        ) {
            return view('redirect.expired');
        }

        // 7. PIN protection
        if ($shortLink->pin_code) {
            if (!$request->session()->get('pin_passed_' . $shortLink->id)) {
                return view('redirect.pin', [
                    'code' => $code,
                    'hint' => $shortLink->password_hint
                ]);
            }
        }

        // 8. One time link
        if ($shortLink->one_time && $shortLink->click_count > 0) {
            return view('redirect.expired');
        }

        // 9. Update click
        $shortLink->increment('click_count');
        $shortLink->update([
            'last_clicked_at' => now()
        ]);

        // 10. Redirect logic
        if ($shortLink->destination_type === 'file') {
            /**
             * nanti:
             * - generate session token
             * - redirect ke secure file controller
             */
            return redirect()->route('file.preview', $shortLink->short_code);
        }

        // 11. Normal URL redirect (pakai countdown / preview)
        return view('redirect.countdown', [
            'target' => $shortLink->destination_url,
            'note'   => $shortLink->note,
            'title'  => $shortLink->title,
        ]);
    }
}
