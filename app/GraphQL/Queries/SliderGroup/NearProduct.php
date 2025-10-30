<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Models\Product;
use App\Models\Setting;

final  class NearProduct
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        if (auth()->check() && auth()->user()->city_id != null) {
            return Product::active()->product()->upTo20()->where('city_id', auth()->user()->city_id)->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->take(5)->get();

        }
        return [];
    }
}
