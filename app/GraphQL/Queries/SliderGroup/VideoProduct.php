<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Models\Product;
use App\Models\Setting;

final  class VideoProduct
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        return Product::active()->product()->upTo20()->whereNotNull('video')
            ->where('video', 'REGEXP', '^(https?:\/\/[^\s]+)$')
            ->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->take(5)->get();
    }
}
