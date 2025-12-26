<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShortlinkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\FileController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/shorten', [ShortlinkController::class, 'store'])->name('shorten');
Route::post('/shorten/update', [ShortlinkController::class, 'update'])->name('shorten.update');
Route::post('/upload', [ShortlinkController::class, 'upload'])->name('upload');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/f/{code}', [FileController::class, 'preview'])
    ->name('file.preview');

Route::get('/f/{code}/download', [FileController::class, 'download'])
    ->name('file.download');

Route::get('/f/{code}/stream', [FileController::class, 'stream'])
    ->name('file.stream'); // untuk img/video/pdf

Route::get('/{code}', [RedirectController::class, 'handle'])
    ->where('code', '[A-Za-z0-9]+');

