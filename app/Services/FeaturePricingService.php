<?php

namespace App\Services;

use App\Models\Feature;
use App\Models\FeatureGrant;
use App\Models\User;

class FeaturePricingService
{
    public function priceMap(User $user): array
    {
        $features = Feature::with(['prices', 'discounts'])->get();

        $grants = FeatureGrant::where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->get()
            ->keyBy('feature.code');

        $result = [];

        foreach ($features as $feature) {
            $price = optional(
                $feature->prices->firstWhere('tier', $user->tier ?? 'basic')
            )->price_coins ?? 0;

            // DISCOUNT
            foreach ($feature->discounts as $discount) {
                if ($discount->isActive()) {
                    if ($discount->type === 'percentage') {
                        $price -= ($price * $discount->value / 100);
                    } else {
                        $price -= $discount->value;
                    }
                }
            }

            // GRANT
            if ($grants->has($feature->code)) {
                $price = 0;
            }

            $result[$feature->code] = max(0, (int) $price);
        }

        return $result;
    }

    /**
     * Validasi & normalize charges dari frontend
     */
    public function validateCharges(array $clientCharges, User $user): array
    {
        $priceMap = $this->priceMap($user);
        $validCharges = [];

        foreach ($clientCharges as $item) {
            $key = $item['key'] ?? null;
            if (!$key) continue;

            $serverPrice = $priceMap[$key] ?? null;

            // Feature tidak dikenal
            if ($serverPrice === null) {
                abort(403, 'Invalid feature: ' . $key);
            }

            // Harga dimanipulasi
            if ((int) $item['price'] !== (int) $serverPrice) {
                abort(403, 'Invalid price detected');
            }

            if ($serverPrice > 0) {
                $validCharges[] = [
                    'key'   => $key,
                    'label'=> $item['label'] ?? $key,
                    'price'=> $serverPrice,
                ];
            }
        }

        return $validCharges;
    }
}
