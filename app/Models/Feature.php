<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    public function prices()
    {
        return $this->hasMany(FeaturePrice::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_feature');
    }

}

