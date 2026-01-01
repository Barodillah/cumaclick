<?php

namespace App\Http\Controllers;

use App\Models\{
    Feature,
    FeaturePrice,
    Discount,
    FeatureGrant,
    User,
    Topup
};
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    private array $tiers = ['basic', 'premium', 'diamond'];

    private function authorizeAdmin()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Admin only');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        return view('admin.features.index', [
            'features' => Feature::latest()->with('prices')->get(),
            'discounts' => Discount::latest()->get(),
            'users' => User::select('id', 'name', 'email')->get(),
            'tiers' => $this->tiers,
        ]);
    }

    public function admin()
    {
        $this->authorizeAdmin();

        return view('admin.index', [
            'totalUsers'    => \App\Models\User::count(),
            'activeUsers'   => \App\Models\User::whereNotNull('email_verified_at')->count(),
            'totalRevenue'  => \App\Models\Topup::where('transaction_status', 'success')
                                    ->sum('gross_amount'),
            'users'         => \App\Models\User::latest()->paginate(10),
            'topups'        => \App\Models\Topup::with('user')->latest()->paginate(10), // ambil data topup terbaru
        ]);
    }

    public function storeFeature(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'code' => 'required|string|unique:features,code',
            'name' => 'required|string',
        ]);

        Feature::create($request->only('code', 'name'));

        return back()->with('success', 'Feature berhasil ditambahkan');
    }

    public function storePrice(Request $request, Feature $feature)
    {
        $this->authorizeAdmin();

        $request->validate([
            'tier' => 'required|in:basic,premium,diamond',
            'price_coins' => 'required|integer|min:0',
        ]);

        FeaturePrice::updateOrCreate(
            [
                'feature_id' => $feature->id,
                'tier' => $request->tier,
            ],
            [
                'price_coins' => $request->price_coins,
            ]
        );

        return back()->with('success', 'Harga fitur diperbarui');
    }

    public function storeDiscount(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'code' => 'required|unique:discounts,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
        ]);

        Discount::create($request->only('code', 'type', 'value'));

        return back()->with('success', 'Diskon berhasil dibuat');
    }

    public function storeGrant(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'feature_id' => 'required|exists:features,id',
            'quota' => 'nullable|integer|min:1',
        ]);

        FeatureGrant::create([
            'user_id' => $request->user_id,
            'feature_id' => $request->feature_id,
            'quota' => $request->quota,
            'created_by' => auth()->id(),
            'reason' => 'Admin Grant',
        ]);

        return back()->with('success', 'Fitur berhasil digratiskan ke user');
    }

    public function priceMap()
    {
        $user = auth()->user();

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

            /* === DISCOUNT === */
            foreach ($feature->discounts as $discount) {
                if ($discount->isActive()) {
                    if ($discount->type === 'percentage') {
                        $price -= ($price * $discount->value / 100);
                    } else {
                        $price -= $discount->value;
                    }
                }
            }

            /* === GRANT === */
            if ($grants->has($feature->code)) {
                $price = 0;
            }

            $result[$feature->code] = max(0, (int) $price);
        }

        return response()->json($result);
    }

    public function options()
    {
        $user = auth()->user();

        $allowed = match ($user->tier) {
            'basic'   => ['upgrade_premium', 'upgrade_diamond'],
            'premium' => ['upgrade_diamond'],
            default   => []
        };

        if (empty($allowed)) {
            return response()->json(['data' => []]);
        }

        // Hardcode benefit
        $benefits = [
            'upgrade_premium' => [
                'Discount fitur 50%',
            ],
            'upgrade_diamond' => [
                'All Premium benefits',
                'Pasti bebas iklan',
                'Discount 85% feature dan sebagian gratis',
                'Highest priority support',
            ],
        ];

        $features = Feature::whereIn('code', $allowed)
            ->with(['prices' => function ($q) use ($user) {
                $q->where('tier', $user->tier);
            }])
            ->get()
            ->map(function ($f) use ($benefits) {

                $price = $f->prices->first(); // hasil filter tier

                if (!$price) {
                    return null; // ⬅️ skip jika tidak ada harga
                }

                return [
                    'code'    => $f->code,
                    'name'    => $f->name,
                    'benefit' => $benefits[$f->code] ?? [],
                    'price'   => $price->price_coins, // ✅ BENAR
                ];
            })
            ->filter()
            ->values();

        return response()->json(['data' => $features]);
    }

}


