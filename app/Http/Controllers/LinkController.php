<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortLink; // Pastikan ini model yang menyimpan link
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin bisa lihat semua link
            $links = ShortLink::all();
        } else {
            // User biasa hanya bisa lihat link miliknya sendiri
            $links = ShortLink::where('user_id', $user->id)->get();
        }

        return view('links.index', compact('links'));
    }
}
