<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OneTimeLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_link_id',
        'token_hash',
        'activated_at',
        'used_at',
        'used_ip',
        'used_ua',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'used_at'      => 'datetime',
    ];

    /**
     * Relasi ke ShortLink
     */
    public function shortLink()
    {
        return $this->belongsTo(ShortLink::class);
    }

    /**
     * Scope: link masih aktif & belum dipakai
     */
    public function scopeActive($query)
    {
        return $query
            ->whereNotNull('activated_at')
            ->whereNull('used_at');
    }
}
