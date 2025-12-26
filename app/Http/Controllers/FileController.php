<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function preview($code)
    {
        $short = ShortLink::where('short_code', $code)->firstOrFail();

        if ($short->destination_type !== 'file') {
            abort(404);
        }

        $path = $short->destination_url;

        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return view('file.viewer', [
            'short' => $short,
            'ext'   => $ext,
            'type'  => $this->detectType($ext),
        ]);
    }

    public function stream($code)
    {
        $short = ShortLink::where('short_code', $code)->firstOrFail();

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

