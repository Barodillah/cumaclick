<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturePrice extends Model
{
    protected $fillable = [
        'feature_id',
        'tier',
        'price_coins',
    ];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}

