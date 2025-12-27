<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use App\Models\Click;
use Jenssegers\Agent\Agent;


class RedirectController extends Controller
{
    public function handle(Request $request, string $code)
    {
        // WAKTU JAKARTA
        $now = now('Asia/Jakarta');

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
        if ($shortLink->expired_at && $now->gt($shortLink->expired_at)) {
            return view('redirect.expired');
        }

        // 5. Jadwal aktif
        if ($shortLink->active_from && $now->lt($shortLink->active_from)) {
            return view('redirect.not-found');
        }

        if ($shortLink->active_until && $now->gt($shortLink->active_until)) {
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

        // 9. Update click (pakai waktu Jakarta)
        // 9. Simpan click detail (HANYA 1x per request valid)
        $this->logClick($request, $shortLink);

        // Update counter
        $shortLink->increment('click_count');
        $shortLink->update([
            'last_clicked_at' => $now
        ]);


        // Countdown
        if (!$request->has('go')) {
            return view('redirect.countdown', [
                'target' => url()->current() . '?go=1',
                'url'    => $shortLink->destination_url,
                'note'   => $shortLink->note,
                'title'  => $shortLink->title,
            ]);
        }

        // STEP 2 — setelah countdown
        if ($shortLink->destination_type === 'file') {
            return redirect()->route('file.preview', $shortLink->short_code);
        }

        return redirect()->away($shortLink->destination_url);
    }

    public function submitPin(Request $request, string $code)
    {
        $request->validate([
            'pin' => 'required|digits:4'
        ]);

        $shortLink = ShortLink::where('short_code', $code)
            ->orWhere('custom_alias', $code)
            ->first();

        if (!$shortLink || !$shortLink->pin_code) {
            return view('redirect.not-found');
        }

        // PIN SALAH
        if ($request->pin !== $shortLink->pin_code) {
            return back()->with('error', 'PIN yang Anda masukkan salah');
        }

        // PIN BENAR → simpan ke session
        session([
            'pin_passed_' . $shortLink->id => true
        ]);

        // balik ke URL utama (GET)
        return redirect()->to(url($code));
    }

    private function logClick(Request $request, ShortLink $shortLink)
    {
        $agent = new Agent();

        Click::create([
            'short_link_id' => $shortLink->id,
            'short_code'    => $shortLink->short_code,

            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer'    => $request->headers->get('referer'),

            // Device
            'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
            'device_brand'=> $agent->device(),
            'device_model'=> $agent->platform(),

            // OS & Browser
            'os'          => $agent->platform(),
            'os_version'  => $agent->version($agent->platform()),
            'browser'     => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),

            // Tambahan
            'language' => substr($request->getPreferredLanguage(), 0, 10),
            'is_bot'   => $agent->isRobot(),

            // UTM
            'utm_source'   => $request->query('utm_source'),
            'utm_medium'   => $request->query('utm_medium'),
            'utm_campaign' => $request->query('utm_campaign'),

            'clicked_at' => now('Asia/Jakarta'),
        ]);
    }

}
