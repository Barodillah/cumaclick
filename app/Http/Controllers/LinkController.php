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
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $type = $request->type;
        $status = $request->status;
        $tag = $request->tag;

        $user = Auth::user();
        $now = now();

        $links = ShortLink::when($user->role !== 'admin', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->when($q, function ($query) use ($q) {
                    $query->where('short_code', 'like', "%{$q}%")
                        ->orWhere('destination_url', 'like', "%{$q}%")
                        ->orWhere('note', 'like', "%{$q}%")
                        ->orWhere('custom_alias', 'like', "%{$q}%")
                        ->orWhere('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                })
                ->when($startDate, function($query) use ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                })
                ->when($endDate, function($query) use ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                })
                ->when($type, function($query) use ($type) {
                    $query->where('destination_type', $type);
                })
                ->when($tag, function ($query) use ($tag) {
                    $query->whereHas('tags', function ($q) use ($tag) {
                        $q->where('name', $tag);
                    });
                })
                ->when($status, function($query) use ($status, $now) {
                    if($status === 'active') {
                        $query->where('is_active', 1)
                            ->whereNull('blocked_at')
                            ->where(function($q) use ($now) {
                                $q->whereNull('expired_at')
                                    ->orWhere('expired_at', '>', $now);
                            })
                            ->where(function($q) use ($now) {
                                $q->whereNull('active_from')
                                    ->orWhere('active_from', '<=', $now);
                            })
                            ->where(function($q) use ($now) {
                                $q->whereNull('active_until')
                                    ->orWhere('active_until', '>=', $now);
                            })
                            ->where(function($q) {
                                $q->whereNull('max_click')
                                    ->orWhereColumn('click_count', '<', 'max_click');
                            })
                            ->where(function($q) {
                                $q->where('one_time', 0)
                                ->orWhere(function($q2) {
                                    $q2->where('one_time', 1)
                                        ->where('click_count', 0); // <-- gunakan where literal, bukan whereColumn
                                });
                            });
                    } elseif($status === 'expired') {
                        $query->where(function($q) use ($now) {
                            $q->where('expired_at', '<', $now)
                            ->orWhere(function($q2) use ($now) {
                                $q2->whereNotNull('active_until')
                                    ->where('active_until', '<', $now);
                            })
                            ->orWhere(function($q3) {
                                $q3->whereNotNull('max_click')
                                    ->whereColumn('click_count', '>=', 'max_click');
                            })
                            ->orWhere(function($q4) {
                                $q4->where('one_time', 1)
                                ->where('click_count', '>', 0); // <-- literal
                            });
                        });
                    } elseif($status === 'blocked') {
                        $query->where('is_active', 0)
                            ->orWhereNotNull('blocked_at');
                    }
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

    public function claim(Request $request)
    {
        $data = $request->validate([
            'short_code' => 'required|string|exists:short_links,short_code',
        ]);

        $link = ShortLink::where('short_code', $data['short_code'])->firstOrFail();

        if ($link->user_id && $link->user_id !== auth()->id()) {
            return back()->withErrors([
                'short_code' => 'Shortlink ini sudah dimiliki oleh pengguna lain.'
            ]);
        }

        // Cek apakah link sudah dimiliki
        if ($link->user_id) {
            return redirect()->back()
                ->withErrors(['short_code' => 'Shortlink ini sudah dimiliki oleh pengguna lain.'])
                ->withFragment('claim');
        }

        // Assign link ke user saat ini
        $link->user_id = auth()->id();
        $link->save();

        return redirect()->route('links.edit', $link->short_code)
            ->with('msg', 'Shortlink berhasil diklaim dan sekarang menjadi milik Anda.');
    }

    public function dashboard()
    {
        $user = auth()->user();

        // BASE QUERY (scope user / admin)
        $baseQuery = ShortLink::query();

        if ($user->role !== 'admin') {
            $baseQuery->where('user_id', $user->id);
        }

        /* =========================
        | TOP 5 UNIQUE CLICK
        ========================= */
        $topLinks = (clone $baseQuery)
            ->withCount([
                'clicks as unique_click_count' => function ($q) {
                    $q->select(\DB::raw('COUNT(DISTINCT ip_address)'));
                }
            ])
            ->orderByDesc('unique_click_count')
            ->limit(5)
            ->get();

        /* =========================
        | STATISTIK
        ========================= */
        $totalLinks = (clone $baseQuery)->count();

        $totalClicks = (clone $baseQuery)->sum('click_count');

        $uniqueClicks = \App\Models\Click::whereIn(
                'short_link_id',
                (clone $baseQuery)->pluck('id')
            )
            ->distinct('ip_address')
            ->count('ip_address');

        $conversionRate = $totalLinks > 0
            ? ($totalClicks / $totalLinks) * 100
            : 0;

        /* =========================
        | LIST LINK (DEFAULT VIEW)
        ========================= */
        $links = (clone $baseQuery)
            ->latest()
            ->when($user->role === 'admin', fn ($q) => $q->with('user'))
            ->get();

        return view('links.dashboard', compact(
            'links',
            'totalLinks',
            'totalClicks',
            'uniqueClicks',
            'conversionRate',
            'topLinks'
        ));
    }

    public function premium()
    {
        return view('links.premium');
    }
}
