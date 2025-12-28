<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\ShortLink;

class AuthController extends Controller
{
    // tampilkan form login
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect('/links');
        }
        return view('auth.login');
    }

    // proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'adminlink' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('The '.$attribute.' must be a valid email address.');
                    }
                },
            ],
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->role != 'admin' && is_null($user->email_verified_at)) {

            $otp = Otp::where('user_id', $user->id)
                ->where('type', 'email_verification')
                ->where('used', 0)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (!$otp) {
                $otp = Otp::create([
                    'user_id'    => $user->id,
                    'code'       => rand(100000, 999999),
                    'type'       => 'email_verification',
                    'expires_at' => now()->addMinutes(5),
                ]);

                Mail::to($user->email)->send(
                    new OtpMail($otp->code, 'email_verification')
                );
            }

            return redirect()->route('otp.form', [
                'email' => $user->email,
                'type'  => 'email_verification'
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('links.index'))->with('success', 'Login berhasil, welcome back!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $otp = rand(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'code' => $otp,
            'type' => 'email_verification',
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp, 'email_verification'));
        return redirect()->route('otp.form', ['email' => $user->email,'type'  => 'email_verification']);
    }

    public function resendOtp(Request $request)
    {
        // Pastikan ada query email
        $email = $request->query('email');
        $type = $request->query('type');
        if (!$email) {
            return back()->with('error', 'Email tidak ditemukan untuk resend OTP.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->with('error', 'User dengan email ini tidak ditemukan.');
        }

        // Buat OTP baru
        $otp = rand(100000, 999999);

        // Tandai OTP lama sebagai expired / used
        Otp::where('user_id', $user->id)
            ->where('used', 0)
            ->update(['used' => 1]);

        // Simpan OTP baru
        Otp::create([
            'user_id' => $user->id,
            'code' => $otp,
            'type' => $type,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Kirim email OTP baru
        Mail::to($user->email)->send(new OtpMail($otp, $type));

        return redirect()->route('otp.form', ['email' => $user->email, 'type' => $type])
                        ->with('success', 'OTP baru telah dikirim ke email Anda.');
    }

    public function verifyOtpForm(Request $request)
    {
        return view('auth.verify-otp', [
            'email' => $request->email,
            'type' => $request->type
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'type' => 'required'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Ambil OTP berdasarkan user, code, dan type
        $otp = Otp::where('user_id', $user->id)
                ->where('code', $request->code)
                ->where('type', $request->type) // tambahkan ini
                ->where('used', 0)
                ->first();

        if (!$otp) {
            return back()->with('error', 'OTP salah, coba lagi.');
        }

        if ($otp->expires_at < now()) {
            return back()->with('error', 'OTP expired, silakan kirim ulang.');
        }

        // Tandai OTP sudah digunakan
        $otp->update(['used' => 1]);

        // Jika OTP untuk registrasi
        if ($otp->type === 'email_verification') {

            // Verifikasi email
            $user->update([
                'email_verified_at' => now()
            ]);

            // Login user
            auth()->login($user);

            return redirect()->route('links.index')
                            ->with('success', 'Email berhasil diverifikasi!');
        }

        // Jika OTP untuk reset password
        if ($otp->type === 'password_reset') {

            // Simpan data dalam session agar bisa ganti password
            session(['reset_email' => $user->email]);

            return redirect()->route('forgot.reset')
                            ->with('success', 'Silakan buat password baru.');
        }

        return back()->with('error', 'Tipe OTP tidak dikenali.');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // profile
    public function profile()
    {
        $user = auth()->user();
        return view('auth.profile', compact('user'));
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('profile')->with('success', 'Name updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }
}
