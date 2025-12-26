<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShortlinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'destination_url' => 'required|url'
        ]);

        do {
            $shortCode = Str::random(6);
        } while (ShortLink::where('short_code', $shortCode)->exists());

        $short = ShortLink::create([
            'destination_type' => 'url',
            'destination_url'  => $request->destination_url,
            'short_code'       => $shortCode,
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $short->short_code,
            'destination' => $short->destination_url,
            'short_url'   => url($short->short_code),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120'
        ]);

        $path = $request->file('file')->store('uploads', 'private');

        do {
            $shortCode = Str::random(6);
        } while (ShortLink::where('short_code', $shortCode)->exists());

        $short = ShortLink::create([
            'destination_type' => 'file',
            'destination_url'  => $path,
            'short_code'       => $shortCode,
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $short->short_code,
            'destination' => 'FILE',
            'short_url'   => url($short->short_code),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'old_code'   => 'required|exists:short_links,short_code',
            'short_code' => 'required|alpha_dash|min:3|max:50|unique:short_links,short_code',
        ]);

        $short = ShortLink::where('short_code', $request->old_code)->firstOrFail();

        $short->update([
            'short_code' => $request->short_code,
        ]);

        return redirect()->back()->with('short_result', [
            'code'        => $short->short_code,
            'destination' => $short->destination_type === 'url'
                                ? $short->destination_url
                                : 'FILE',
            'short_url'   => url($short->short_code),
        ]);
    }

}
