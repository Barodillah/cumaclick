<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_link_id',
        'short_code',
        'ip_address',
        'user_agent',
        'referer',

        'country',
        'country_code',
        'region',
        'city',
        'timezone',
        'latitude',
        'longitude',

        'device_type',
        'device_brand',
        'device_model',
        'os',
        'os_version',
        'browser',
        'browser_version',

        'is_bot',
        'language',
        'utm_source',
        'utm_medium',
        'utm_campaign',

        'clicked_at',
    ];

    protected $casts = [
        'is_bot'    => 'boolean',
        'clicked_at' => 'datetime',
    ];

    public function shortLink()
    {
        return $this->belongsTo(ShortLink::class);
    }
}
