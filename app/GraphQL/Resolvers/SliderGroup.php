<?php

namespace App\GraphQL\Resolvers;

use App\Models\Like;
use App\Models\Product;
use App\Models\Rate;
use App\Models\Setting;
use DB;

class SliderGroup
{

    /**
     * @param $root Product
     * @return float
     */
    public static function latest($root): mixed
    {


    }

    public static function near($root): mixed
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        if (auth()->check() && auth()->user()->city_id != null) {
            return Product::active()->product()->upTo20()->where('city_id', auth()->user()->city_id)->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->take(5);

        }
        return [];

    }

    public static function videos($root): mixed
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        return Product::active()->product()->upTo20()->whereNotNull('video')->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->take(5);


    }

    public static function activity($root): mixed
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        return Product::active()->product()->upTo20()->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->orderByDesc(DB::raw('likes_count + comments_count'))->take(5);


    }

    public static function specials($root): mixed
    {
        $setting = Setting::first();
        $days = $setting?->options['recommended_month'] ?? 30;
        return Product::active()->product()->where('power', 100)->whereBetween('created_at', [now()->subDays($days), now()])->inRandomOrder()->orderByDesc(DB::raw('likes_count + comments_count'))->take(5);


    }

    public static function sellers($root): float
    {
        return 10;

    }

    public static function sellersActivity($root): float
    {
        return 20;

    }


}
