<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Models\Product;
use App\Models\Setting;

final  class SpecialProduct
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        return Product::active()->product()->where('power', 100)->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->take(5)->get();

    }
}
