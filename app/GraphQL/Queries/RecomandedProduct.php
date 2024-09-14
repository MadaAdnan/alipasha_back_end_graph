<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\LevelProductEnum;
use App\Models\Interaction;
use App\Models\Product;
final class RecomandedProduct
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        if (auth()->user()) {
            $userId = auth()->id();
            $mostVisitedCategoryId = Interaction::where('user_id', $userId)
                ->orderBy('visited', 'desc')
                ->value('category_id');
            $mostVisitedProductsQuery = Product::where('sub1_id', $mostVisitedCategoryId);
            $followedSellers = Interaction::where('user_id', $userId)
                ->whereNotNull('seller_id')
                ->pluck('seller_id');
            $followedSellerProductsQuery = Product::whereIn('user_id', $followedSellers);
        } else {
            // في حالة عدم وجود المستخدم، يتم جلب المنتجات المميزة فقط
            $mostVisitedProductsQuery =Product::whereRaw('1=0'); // يجب أن يكون الاستعلام فارغًا
            $followedSellerProductsQuery = Product::whereRaw('1=0'); // يجب أن يكون الاستعلام فارغًا
        }

// جلب المنتجات المميزة
        $featuredProductsQuery = Product::where('level', LevelProductEnum::SPECIAL->value)->take(10);

// دمج النتائج وترتيبها وتقسيمها إلى صفحات
        $products = Product::fromSub(function ($query) use ($featuredProductsQuery, $mostVisitedProductsQuery, $followedSellerProductsQuery) {
            $query->select('*')
                ->fromSub($featuredProductsQuery, 'featured')
                ->union($mostVisitedProductsQuery)
                ->union($followedSellerProductsQuery);
        }, 'combined')
            ->orderBy('level', 'desc')
            ->orderBy('sub1_id', 'desc')
            ->orderBy('user_id', 'desc');
    }
}
