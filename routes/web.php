<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/shorten', [ShortlinkController::class, 'store'])->name('shorten');
Route::post('/shorten/update', [ShortlinkController::class, 'update'])->name('shorten.update');
Route::post('/upload', [ShortlinkController::class, 'upload'])->name('upload');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/{code}', [RedirectController::class, 'handle'])
    ->where('code', '[A-Za-z0-9]+');

