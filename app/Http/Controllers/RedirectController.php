<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use App\Models\Click;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Http;
use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;


class RedirectController extends Controller
{
    public function handle(Request $request, string $code)
    {
        // WAKTU JAKARTA
        $now = now('Asia/Jakarta');

        $ads = Http::withoutVerifying()->get('https://cuma.click/ads/api.php')->json();

        // 1. Cari berdasarkan short_code atau custom_alias
        $shortLink = ShortLink::where('short_code', $code)
            ->orWhere('custom_alias', $code)
            ->first();

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
        if ($shortLink->max_click !== null && $shortLink->click_count >= $shortLink->max_click) {
            return view('redirect.expired');
        }

        // CEK SPAM KLIK BERDASARKAN IP
        $ip = $request->ip();
        $oneMinuteAgo = $now->copy()->subMinute();

        $recentClicks = \App\Models\Click::where('short_link_id', $shortLink->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', $oneMinuteAgo)
            ->count();

        // Jika lebih dari 50 klik dalam 1 menit → abuse_score +5
        if ($recentClicks > 50) {
            $shortLink->increment('abuse_score', 5);
        }

        // jika abuse_score >= 10 → blokir
        if ($shortLink->abuse_score >= 10) {
            $shortLink->update([
                'is_active' => 0,
                'blocked_at' => $now
            ]);
            return view('redirect.not-found');
        }

        // 7. PIN protection
        if ($shortLink->pin_code) {
            if (!$request->session()->get('pin_passed_' . $shortLink->id)) {
                return view('redirect.pin', [
                    'code' => $code,
                    'hint' => $shortLink->password_hint,
                    'ads'  => $ads,
                ]);
            }
        }

        // 8. One time link
        if ($shortLink->one_time && $shortLink->click_count > 0) {
            return view('redirect.expired');
        }

        if ($shortLink->require_otp) {
            if (!$request->session()->get('otp_passed_' . $shortLink->short_code)) {
                $otp = rand(1000, 9999);
                $user = $shortLink->user;
                Otp::create([
                                'user_id' => $user->id,
                                'code' => $otp,
                                'type' => 'open_link',
                                'expires_at' => now()->addMinutes(5),
                            ]);

                Mail::to($user->email)->send(new OtpMail($otp, 'open_link'));
                return view('redirect.otp', [
                    'code'  => $code,
                    'email' => $user->email,
                    'type'  => 'open_link',
                    'ads'     => $ads,
                ]);    
            }
        }

        // 9. Update click
        $this->logClick($request, $shortLink);
        $shortLink->increment('click_count');
        $shortLink->update([
            'last_clicked_at' => $now
        ]);

        // Preview
        if ($shortLink->enable_preview) {
            return view('redirect.preview', [
                'target' => $shortLink->destination_type === 'file'
                    ? route('file.preview', $shortLink->short_code)
                    : $shortLink->destination_url,
                'note'  => $shortLink->note,
                'url'   => $shortLink->destination_url,
                'title' => $shortLink->title,
                'ads'   => $ads,
            ]);
        }

        // jika klik count masih dibawah 5 click melalui basic redirect
        if ($shortLink->click_count <= 5) {
            return view('redirect.basic', [
                'target' => $shortLink->destination_type === 'file'
                    ? route('file.preview', $shortLink->short_code)
                    : $shortLink->destination_url,
                'ads'   => $ads,
            ]);
        }

        // jika menonaktifkan iklan
        if (!$shortLink->enable_ads) {
            return view('redirect.basic', [
                'target' => $shortLink->destination_type === 'file'
                    ? route('file.preview', $shortLink->short_code)
                    : $shortLink->destination_url,
                'ads'   => null,
            ]);
        }

        // Countdown
        return view('redirect.countdown', [
            'target' => $shortLink->destination_type === 'file'
                ? route('file.preview', $shortLink->short_code)
                : $shortLink->destination_url,
            'note'  => $shortLink->note,
            'url'   => $shortLink->destination_url,
            'title' => $shortLink->title,
            'ads'   => $ads,
        ]);
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

        // PIN SALAH → tambah abuse_score +2
        if ($request->pin !== $shortLink->pin_code) {
            $shortLink->increment('abuse_score', 2);

            // jika abuse_score >= 10 → blokir
            if ($shortLink->abuse_score >= 10) {
                $shortLink->update([
                    'is_active' => 0,
                    'blocked_at' => now('Asia/Jakarta')
                ]);
            }

            return back()->with('error', 'PIN yang Anda masukkan salah');
        }

        // PIN BENAR → simpan ke session
        session(['pin_passed_' . $shortLink->id => true]);

        // balik ke URL utama (GET)
        return redirect()->to(url($code));
    }

    public function submitOtp(Request $request, string $code)
    {
        $request->validate([
            'code' => 'required|digits:4'
        ]);

        $shortLink = ShortLink::where('short_code', $code)
            ->orWhere('custom_alias', $code)
            ->first();

        if (!$shortLink || !$shortLink->require_otp) {
            return view('redirect.not-found');
        }

        $user = $shortLink->user;

        // Ambil OTP berdasarkan user, code, dan type
        $otp = Otp::where('user_id', $user->id)
                ->where('code', $request->code)
                ->where('type', 'open_link')
                ->where('used', 0)
                ->first();

        if (!$otp) {
            $shortLink->increment('abuse_score', 3);

            // jika abuse_score >= 10 → blokir
            if ($shortLink->abuse_score >= 10) {
                $shortLink->update([
                    'is_active' => 0,
                    'blocked_at' => now('Asia/Jakarta')
                ]);
            }

            return back()->with('error', 'OTP salah, coba lagi.');
        }

        if ($otp->expires_at < now()) {
            return back()->with('error', 'OTP telah kadaluarsa, silakan minta OTP baru.');
        }

        // Tandai OTP sebagai used
        $otp->update(['used' => 1]);

        // Set session untuk membuka link
        session(['otp_passed_' . $shortLink->short_code => true]);

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
