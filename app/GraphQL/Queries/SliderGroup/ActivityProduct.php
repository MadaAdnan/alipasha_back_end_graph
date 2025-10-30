<?php declare(strict_types=1);

namespace App\GraphQL\Queries\SliderGroup;

use App\Models\Product;
use App\Models\Setting;

final  class ActivityProduct
{
    /** @param  array{}  $args */
    public function __invoke( $_, array $args)
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        return Product::active()->product()->upTo20()->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->orderByDesc(DB::raw('likes_count + comments_count'))->take(5)->get();
    }
}
