<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Simpan / replace tag untuk 1 short link
     */
    public function store(Request $request, $shortCode)
    {
        $request->validate([
            'tags' => 'nullable|string'
        ]);

        $shortLink = ShortLink::where('short_code', $shortCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $tags = collect(explode(',', $request->tags))
            ->map(fn ($t) => trim(strtolower($t)))
            ->filter()
            ->unique()
            ->values();

        // Hapus tag lama (replace total → UX konsisten)
        Tag::where('user_id', Auth::id())
            ->where('short_link_id', $shortLink->id)
            ->delete();

        // Simpan tag baru
        foreach ($tags as $tag) {
            Tag::create([
                'user_id'       => Auth::id(),
                'short_link_id' => $shortLink->id,
                'name'          => $tag,
            ]);
        }

        return redirect()->back()->with('success', 'Tags updated successfully.');
    }

    /**
     * Suggest tag berdasarkan histori user
     * Dipakai untuk datalist
     */
    public function suggestions(Request $request)
    {
        $q = $request->get('q');

        return Tag::when($q, fn ($query) =>
                $query->where('name', 'like', "%{$q}%")
            )
            ->select('name')
            ->distinct()
            ->orderBy('name')
            ->limit(10)
            ->pluck('name');
    }

    public function getTags($shortCode)
    {
        $shortLink = ShortLink::where('short_code', $shortCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $shortLink->tags()
            ->orderBy('name')
            ->pluck('name');
    }

    public function distinctByUser()
    {
        return Tag::where('user_id', auth()->id())
            ->select('name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name');
    }

}
