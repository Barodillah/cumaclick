<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\OneTimeLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $files = ShortLink::where('destination_type', 'file')

            // 🔐 Filter user (kecuali admin)
            ->when($user->role !== 'admin', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })

            // 🔍 Search
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;

                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'LIKE', "%{$q}%")
                        ->orWhere('short_code', 'LIKE', "%{$q}%")
                        ->orWhere('destination_url', 'LIKE', "%{$q}%");
                });
            })

            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('files.index', compact('files'));
    }

    public function preview($code)
    {
        $short = ShortLink::where('short_code', $code)->firstOrFail();

        if ($short->destination_type !== 'file') {
            abort(404);
        }

        if ($short->one_time) {

            $token = request('t');

            if (!$token) {
                return view('redirect.expired');
            }

            $valid = OneTimeLink::where('short_link_id', $short->id)
                ->where('token_hash', $token)
                ->whereNull('used_at') // ⬅️ BELUM DIPAKAI
                ->exists();

            if (!$valid) {
                return view('redirect.expired');
            }
        }

        if (!Storage::disk('private')->exists($short->destination_url)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($short->destination_url, PATHINFO_EXTENSION));

        return view('file.viewer', [
            'short' => $short,
            'ext'   => $ext,
            'type'  => $this->detectType($ext),
            'token' => request('t'),
        ]);
    }

    public function stream($code)
    {
        $short = ShortLink::where('short_code', $code)->firstOrFail();

        if ($short->destination_type !== 'file') {
            abort(404);
        }

        if ($short->one_time) {

            $token = request('t');

            if (!$token) {
                abort(403);
            }

            return DB::transaction(function () use ($short, $token) {

                $otl = OneTimeLink::where('short_link_id', $short->id)
                    ->where('token_hash', $token)
                    ->whereNull('used_at')
                    ->lockForUpdate()
                    ->first();

                if (!$otl) {
                    abort(403);
                }

                // 🔥 HABISKAN SAAT STREAM PERTAMA
                $otl->update([
                    'used_at' => now(),
                    'used_ip' => request()->ip(),
                    'used_ua' => request()->userAgent(),
                ]);

                return response()->file(
                    storage_path('app/private/' . $short->destination_url)
                );
            });
        }

        return response()->file(
            storage_path('app/private/' . $short->destination_url)
        );
    }

    public function download($code)
    {
        $short = ShortLink::where('short_code', $code)->firstOrFail();

        return Storage::disk('private')->download(
            $short->destination_url,
            $short->note ? Str::slug($short->note) . '.' . pathinfo($short->destination_url, PATHINFO_EXTENSION) : null
        );
    }

    private function detectType($ext)
    {
        return match (true) {
            in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']) => 'image',
            in_array($ext, ['mp4','webm','ogg','mov','avi','mkv'])       => 'video',
            $ext === 'pdf'                                              => 'pdf',
            in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx'])    => 'office',
            default                                                     => 'other',
        };
    }
}

