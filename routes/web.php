<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\TopupController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/syarat-ketentuan', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/kebijakan-privasi', function () {
    return view('legal.privacy');
})->name('privacy');

Route::post('/shorten', [ShortlinkController::class, 'store'])->name('shorten');
Route::post('/shorten/update', [ShortlinkController::class, 'update'])->name('shorten.update');
Route::post('/upload', [ShortlinkController::class, 'upload'])->name('upload');

Route::get('/otp/verify', [AuthController::class, 'verifyOtpForm'])->name('otp.form');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/resendotp', [AuthController::class, 'resendOtp'])->name('otp.resend');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password - Form Input Email
Route::get('/forgot', [AuthController::class, 'forgotForm'])->name('forgot');

// Proses Kirim OTP ke Email
Route::post('/forgot', [AuthController::class, 'sendOtpForgot'])->name('forgot.send');

// Form Reset Password Baru
Route::get('/forgot/reset', [AuthController::class, 'resetPasswordForm'])->name('forgot.reset');

// Proses Simpan Password Baru
Route::post('/forgot/reset', [AuthController::class, 'resetPassword'])->name('forgot.reset.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/files', [FileController::class, 'index'])
    ->name('files.index');

    Route::get('/links/{id}/one-time/activate', [LinkController::class, 'activate'])
    ->name('links.one-time.activate');

    Route::get('/links/{id}/one-time/deactivate', [LinkController::class, 'deactivate'])
        ->name('links.one-time.deactivate');

    Route::post('/topup', [TopupController::class, 'store']);

    Route::post('/topup-success', [TopupController::class, 'topupSuccess'])->name('topup.success');

    Route::get('/tier/upgrade-options', [FeatureController::class, 'options']);
    Route::post('/tier/upgrade', [WalletController::class, 'upgrade']);

    // fitur admin
    Route::get('/feature/prices/map', [FeatureController::class, 'priceMap'])
    ->name('features.price-map');

    // routes/web.php
    Route::post('/features/pay', [WalletController::class, 'pay'])
        ->name('features.pay');

    Route::get('/admin/features', [FeatureController::class, 'index'])
        ->name('admin.features.index');

    Route::get('/admin', [FeatureController::class, 'admin'])
        ->name('admin.index');

    Route::post('/admin/features', [FeatureController::class, 'storeFeature'])
        ->name('admin.features.store');

    Route::post('/admin/features/{feature}/prices', [FeatureController::class, 'storePrice'])
        ->name('admin.features.prices.store');

    Route::post('/admin/discounts', [FeatureController::class, 'storeDiscount'])
        ->name('admin.discounts.store');

    Route::post('/admin/feature-grants', [FeatureController::class, 'storeGrant'])
        ->name('admin.feature-grants.store');

    Route::get('/wallet', [WalletController::class, 'walletControl'])->name('admin.wallet.index');

    Route::post('/wallet/adjust', [WalletController::class, 'adjustUser'])
        ->name('admin.wallet.adjust');

    Route::post('/wallet/admin-adjust', [WalletController::class, 'adjustAdmin'])
        ->name('admin.wallet.admin.adjust');

    Route::post('/wallet/enable-ad-free', [WalletController::class, 'enableAdFree'])
    ->name('wallet.enableAdFree');

    Route::post('/claim', [LinkController::class, 'claim'])->name('claim');

    Route::post('/wallet/claim-register-bonus', 
        [WalletController::class, 'claimRegisterBonus']
    )->name('wallet.claim-register-bonus');

    Route::get('/dashboard', [LinkController::class, 'dashboard'])->name('links.dashboard');
    Route::get('/premium', [LinkController::class, 'premium'])->name('links.premium');
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::get('/links/search', [LinkController::class, 'search'])->name('links.search');
    
    Route::delete('/links/{short_code}', [LinkController::class, 'destroy'])
    ->name('links.delete');

    Route::get('/links/{short_code}/edit', [LinkController::class, 'edit'])
        ->name('links.edit');

    Route::put('/links/{short_code}', [LinkController::class, 'update'])
        ->name('links.update');

    Route::get('/links/{short_code}/clicks', [LinkController::class, 'clicks'])
        ->name('links.clicks');

    Route::get('/links/{short_code}/observation', [LinkController::class, 'observation'])
        ->name('links.observation');

    Route::post('/links/{shortCode}/tags', [TagController::class, 'store'])
        ->name('links.addTags');

    Route::get('/tags/suggestions', [TagController::class, 'suggestions'])
        ->name('tags.suggestions');

    Route::get('/links/{shortCode}/tags', [TagController::class, 'getTags'])
    ->name('links.getTags');

    Route::get('/tags/distinct', [TagController::class, 'distinctByUser'])
    ->name('tags.distinct');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile/name', [AuthController::class, 'updateName'])->name('profile.updateName');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.updatePassword');
});

Route::get('/f/{code}', [FileController::class, 'preview'])
    ->name('file.preview');

Route::get('/f/{code}/download', [FileController::class, 'download'])
    ->name('file.download');

Route::get('/f/{code}/stream', [FileController::class, 'stream'])
    ->name('file.stream'); // untuk img/video/pdf

Route::post('/{code}/pin', [RedirectController::class, 'submitPin'])
    ->name('redirect.pin');

Route::post('/{code}/otp', [RedirectController::class, 'submitOtp'])
    ->name('redirect.otp');

Route::get('/{code}', [RedirectController::class, 'handle'])
    ->where('code', '[A-Za-z0-9]+');

