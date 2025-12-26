<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'short_code',
        'custom_alias',
        'destination_type',
        'destination_url',
        'destination_file_id',
        'pin_code',
        'password_hint',
        'require_otp',
        'is_active',
        'one_time',
        'blocked_at',
        'active_from',
        'active_until',
        'expired_at',
        'max_click',
        'click_count',
        'last_clicked_at',
        'abuse_score',
        'title',
        'description',
        'enable_preview',
        'note',
        'created_ip',
        'created_ua',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'one_time'       => 'boolean',
        'require_otp'    => 'boolean',
        'enable_preview' => 'boolean',
        'blocked_at'     => 'datetime',
        'active_from'    => 'datetime',
        'active_until'   => 'datetime',
        'expired_at'     => 'datetime',
        'last_clicked_at'=> 'datetime',
    ];

    /* ================== RELATIONS ================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(UploadedFile::class, 'destination_file_id');
    }

    /* ================== SCOPES ================== */

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->whereNull('blocked_at');
    }

    /* ================== HELPERS ================== */

    public function isExpired(): bool
    {
        return $this->expired_at && now()->gt($this->expired_at);
    }

    public function reachLimit(): bool
    {
        return $this->max_click !== null && $this->click_count >= $this->max_click;
    }
}
