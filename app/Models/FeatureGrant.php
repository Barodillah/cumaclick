<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureGrant extends Model
{
    protected $fillable = [
        'user_id',
        'feature_id',
        'quota',
        'expired_at',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

