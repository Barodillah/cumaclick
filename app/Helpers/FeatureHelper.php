<?php

use App\Models\Feature;

if (!function_exists('featurePrice')) {
    function featurePrice($code, $tier)
    {
        $feature = Feature::where('code', $code)->first();
        if (!$feature) return 0;

        $price = $feature->prices()->where('tier', $tier)->first();
        return $price ? $price->price_coins : 0;
    }
}
