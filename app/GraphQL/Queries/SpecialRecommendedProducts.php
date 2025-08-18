<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

final class SpecialRecommendedProducts
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // Get the most interacted categories for the authenticated user
        $interactions = Interaction::where('user_id', auth()->id())
            ->select('category_id', DB::raw('SUM(visited) as total_visits'))
            ->groupBy('category_id')
            ->orderBy('total_visits', 'desc')
            ->limit(10)
            ->get();

        $categoryIds = $interactions->pluck('category_id')->toArray();

        // Get special products from these categories
        $query = Product::query()
            ->where('power','>',20)
            ->where(function ($query){
                $query->whereNull('end_date');
                $query->orWhere('end_date', '>=', now());
            })
            ->where('level', LevelProductEnum::SPECIAL->value)
            ->where('active', ProductActiveEnum::ACTIVE->value);

        // If we have category interactions, filter by those categories
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $products = $query
            ->inRandomOrder()
            ->limit(20)
            ->get();

        return $products;
    }
}
