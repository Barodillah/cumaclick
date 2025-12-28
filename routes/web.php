<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LinkController;

Route::get('/', [HomeController::class, 'index'])->name('home');

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

Route::middleware(['auth'])->group(function () {
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

