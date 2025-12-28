<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortLink;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $links = $user->role === 'admin'
            ? ShortLink::with('user')->latest()->get()
            : ShortLink::where('user_id', $user->id)->latest()->get();

        return view('links.index', compact('links'));
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $user = Auth::user();

        $links = ShortLink::when($user->role !== 'admin', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function ($query) use ($q) {
                $query->where('short_code', 'like', "%{$q}%")
                    ->orWhere('destination_url', 'like', "%{$q}%")
                    ->orWhere('note', 'like', "%{$q}%");
            })
            ->latest()
            ->get();

        return view('links.partials.list', compact('links'));
    }

    public function edit($shortCode)
    {
        $link = ShortLink::where('short_code', $shortCode)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('links.edit', compact('link'));
    }

    public function update(Request $request, $shortCode)
    {
        $link = ShortLink::where('short_code', $shortCode)->firstOrFail();

        // BASE RULES (AMAN)
        $rules = [
            'short_code' => [
                                'required',
                                'string',
                                'max:50',
                                function ($attr, $value, $fail) use ($link) {
                                    $exists = \App\Models\ShortLink::where(function ($q) use ($value) {
                                        $q->where('short_code', $value)
                                        ->orWhere('custom_alias', $value);
                                    })
                                    ->where('id', '!=', $link->id)
                                    ->exists();

                                    if ($exists) {
                                        $fail('Short code sudah digunakan.');
                                    }
                                }
                            ],

                            'custom_alias' => [
                                'nullable',
                                'string',
                                'max:50',
                                function ($attr, $value, $fail) use ($link) {
                                    if (!$value) return;

                                    $exists = \App\Models\ShortLink::where(function ($q) use ($value) {
                                        $q->where('short_code', $value)
                                        ->orWhere('custom_alias', $value);
                                    })
                                    ->where('id', '!=', $link->id)
                                    ->exists();

                                    if ($exists) {
                                        $fail('Custom alias sudah digunakan.');
                                    }
                                }
                            ],

            'pin_code'       => 'nullable|string|digits:4',
            'password_hint'  => 'nullable|string|max:255',

            'max_click'      => 'nullable|integer|min:1',
            'active_from'    => 'nullable|date',
            'active_until'   => 'nullable|date|after:active_from',
            'expired_at'     => 'nullable|date',

            'title'          => 'nullable|string|max:100',
            'description'    => 'nullable|string|max:255',
            'note'           => 'nullable|string',
        ];

        // 🔥 VALIDASI KHUSUS JIKA TYPE = URL
        if ($link->destination_type === 'url') {
            $rules['destination_url'] = 'required|url';
        }

        $data = $request->validate($rules);

        // 🔒 PERTAHANKAN destination_type
        $data['destination_type'] = $link->destination_type;

        // CHECKBOX FIX (WAJIB)
        $data['is_active']   = $request->boolean('is_active');
        $data['one_time']    = $request->boolean('one_time');
        $data['enable_preview'] = $request->boolean('enable_preview');
        $data['require_otp'] = $request->boolean('require_otp');

        $link->update($data);

        return redirect()
            ->route('links.edit', $link->short_code)
            ->with('msg', 'Perubahan berhasil disimpan');
    }

    public function destroy($short_code)
    {
        $link = ShortLink::where('short_code', $short_code)->firstOrFail();

        // Optional: pastikan hanya pemilik yang bisa delete
        if ($link->user_id !== auth()->id()) {
            abort(403);
        }

        $link->delete();

        return redirect()
            ->back()
            ->with('success', 'Link berhasil dihapus.');
    }

    public function clicks($shortCode)
    {
        $user = auth()->user();

        $link = ShortLink::where('short_code', $shortCode)
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        $clicks = $link->clicks()
            ->latest('clicked_at')
            ->get();

        // === ANALYTICS ===
        $totalClicks  = $clicks->count();
        $uniqueClicks = $clicks->unique('ip_address')->count();

        $deviceStats  = $clicks->groupBy('device_type')->map->count();
        $browserStats = $clicks->groupBy('browser')->map->count();
        $countryStats = $clicks->groupBy('country')->map->count();

        $dailyClicks = $clicks
            ->groupBy(fn ($c) => $c->clicked_at->format('Y-m-d'))
            ->map->count();

        return view('links.clicks', compact(
            'link',
            'clicks',
            'totalClicks',
            'uniqueClicks',
            'deviceStats',
            'browserStats',
            'countryStats',
            'dailyClicks'
        ));
    }

    public function observation($shortCode)
    {
        $user = auth()->user();

        $link = ShortLink::where('short_code', $shortCode)
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        return view('links.observation', compact('link'));
    }
}
