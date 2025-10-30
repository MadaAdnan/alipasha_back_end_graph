<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Models\Product;
use App\Models\Setting;

final  class LatestProduct
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        $products = Product::active()->product()->upTo20()->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->take(5)->get();
        return $products;
    }
}
