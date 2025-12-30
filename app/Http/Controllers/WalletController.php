<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class WalletController extends Controller
{
    public function claimRegisterBonus(): RedirectResponse
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        // 🔒 Guard 1: wallet wajib ada
        if (! $wallet) {
            return redirect()
                ->route('links.index')
                ->with('error', 'Wallet tidak ditemukan.');
        }

        // 🔒 Guard 2: sudah pernah ada transaksi
        if ($wallet->transactions()->exists()) {
            return redirect()
                ->route('links.index')
                ->with('error', 'Bonus sudah pernah diklaim.');
        }

        DB::transaction(function () use ($wallet) {

            // 💰 Claim bonus (pakai method model)
            $wallet->credit(
                10,
                'register_bonus',
                'Bonus registrasi pertama'
            );

        });

        return redirect()
            ->route('links.index')
            ->with('success', '🎉 Bonus 10 coin berhasil diklaim!');
    }

    public function walletControl()
    {
        $user = Auth::user();

        // Hanya admin (atau user_id = 1) yang boleh akses
        if ($user->role !== 'admin' && $user->id !== 1) {
            abort(403);
        }

        $users = \App\Models\User::with('wallet')
            ->where('id', '!=', 1)
            ->get();

        $adminWallet = Wallet::where('user_id', 1)->first();

        return view('admin.wallet.index', compact('users', 'adminWallet'));
    }

    /**
     * Admin ⇄ User transfer (DEV/TEST)
     */
    public function adjustUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:1',
            'type' => 'required|in:add,subtract',
            'amount' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $amount = $request->amount;

                $adminWallet = Wallet::lockForUpdate()->where('user_id', 1)->first();
                $userWallet  = Wallet::lockForUpdate()->where('user_id', $request->user_id)->first();

                if (!$adminWallet || !$userWallet) {
                    throw new \Exception('Wallet tidak ditemukan');
                }

                if ($request->type === 'add') {
                    if ($adminWallet->balance < $amount) {
                        throw new \Exception('Saldo admin tidak mencukupi');
                    }

                    $adminWallet->decrement('balance', $amount);
                    $userWallet->increment('balance', $amount);

                    WalletTransaction::create([
                        'wallet_id' => $adminWallet->id,
                        'type' => 'debit',
                        'amount' => $amount,
                        'source' => 'admin_transfer',
                        'related_type' => 'user',
                        'related_id' => $request->user_id,
                        'description' => 'Transfer coin ke user',
                    ]);

                    WalletTransaction::create([
                        'wallet_id' => $userWallet->id,
                        'type' => 'credit',
                        'amount' => $amount,
                        'source' => 'admin_transfer',
                        'related_type' => 'admin',
                        'related_id' => 1,
                        'description' => $request->description ?? 'Coin dari admin',
                    ]);
                }

                if ($request->type === 'subtract') {
                    if ($userWallet->balance < $amount) {
                        throw new \Exception('Saldo user tidak mencukupi');
                    }

                    $userWallet->decrement('balance', $amount);
                    $adminWallet->increment('balance', $amount);

                    WalletTransaction::create([
                        'wallet_id' => $userWallet->id,
                        'type' => 'debit',
                        'amount' => $amount,
                        'source' => 'admin_revoke',
                        'related_type' => 'admin',
                        'related_id' => 1,
                        'description' => $request->description ?? 'Coin ditarik admin',
                    ]);

                    WalletTransaction::create([
                        'wallet_id' => $adminWallet->id,
                        'type' => 'credit',
                        'amount' => $amount,
                        'source' => 'admin_revoke',
                        'related_type' => 'user',
                        'related_id' => $request->user_id,
                        'description' => 'Coin dari user',
                    ]);
                }
            });

            return back()->with('success', 'Saldo user berhasil diperbarui');

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    /**
     * Adjust saldo ADMIN saja (DEV stabilizer)
     */
    public function adjustAdmin(Request $request)
    {
        $request->validate([
            'type' => 'required|in:add,subtract',
            'amount' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $wallet = Wallet::lockForUpdate()->where('user_id', 1)->first();

                if (!$wallet) {
                    throw new \Exception('Wallet admin tidak ditemukan');
                }

                $amount = $request->amount;

                if ($request->type === 'subtract' && $wallet->balance < $amount) {
                    throw new \Exception('Saldo admin tidak mencukupi');
                }

                $request->type === 'add'
                    ? $wallet->increment('balance', $amount)
                    : $wallet->decrement('balance', $amount);

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $request->type === 'add' ? 'credit' : 'debit',
                    'amount' => $amount,
                    'source' => 'admin_stabilizer',
                    'related_type' => 'system',
                    'related_id' => null,
                    'description' => $request->description ?? 'Admin balance adjustment (DEV)',
                ]);
            });

            return back()->with('success', 'Saldo admin berhasil diperbarui');

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function enableAdFree()
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        $price = 20;

        if (!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet not found.'
            ], 404);
        }

        if ($wallet->balance < $price) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient coin balance.'
            ], 422);
        }

        DB::transaction(function () use ($wallet, $price, $user) {

            // 1. Kurangi saldo
            $wallet->decrement('balance', $price);

            // 2. Catat transaksi
            $wallet->transactions()->create([
                'type' => 'debit',
                'amount' => $price,
                'source' => 'ad_free_feature',
                'related_type' => 'users',
                'related_id' => $user->id,
                'description' => 'Enable Ad-Free Experience'
            ]);

            // 3. Aktifkan ads-free
            $user->update([
                'enabled_ads' => 1
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Ad-Free successfully enabled. Enjoy your experience!'
        ]);
    }
}
