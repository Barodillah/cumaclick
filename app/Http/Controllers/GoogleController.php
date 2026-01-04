<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // cek user berdasarkan email
        $user = User::where('email', $googleUser->getEmail())->first();

        // FLAG untuk pesan
        $isRegister = false;

        if (!$user) {
            // ===== REGISTER BARU =====
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'password'          => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]);

            // buat wallet otomatis
            $user->wallet()->create([
                'balance' => 0,
            ]);

            $isRegister = true;
        }

        Auth::login($user);

        return redirect()
            ->intended(route('links.index'))
            ->with(
                'success',
                $isRegister
                    ? 'Akun berhasil dibuat dan Anda masuk dengan Google 🎉'
                    : 'Berhasil masuk dengan Google 👋'
            );
    }
}
