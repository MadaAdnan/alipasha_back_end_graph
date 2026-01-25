<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\LevelProductEnum;
use App\Models\Interaction;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final  class RecommendedProduct
{
    public function __invoke($root, array $args, $context, $info): Builder
    {
        // التحقق من وجود المستخدم
        $mostVisitedCategoryId = null;
        $followedSellers = [];

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
            $mostVisitedProductsQuery = Product::whereRaw('1=0'); // يجب أن يكون الاستعلام فارغًا
            $followedSellerProductsQuery = Product::whereRaw('1=0'); // يجب أن يكون الاستعلام فارغًا
        }

        // جلب المنتجات المميزة
        $featuredProductsQuery = Product::where('level', LevelProductEnum::SPECIAL->value)->take(10);

        // دمج النتائج وترتيبها وتقسيمها إلى صفحات
        $productsQuery = Product::fromSub(function ($query) use ($featuredProductsQuery, $mostVisitedProductsQuery, $followedSellerProductsQuery) {
            $query->select('*')
                ->fromSub($featuredProductsQuery->whereRaw('1=1'), 'featured')
                ->union($mostVisitedProductsQuery->whereRaw('1=1'))
                ->union($followedSellerProductsQuery->whereRaw('1=1'));
        }, 'combined')
            ->orderByRaw("
                CASE
                    WHEN level = '".LevelProductEnum::SPECIAL->value."' THEN 0
                    WHEN sub1_id = $mostVisitedCategoryId THEN 1
                    WHEN user_id IN (".implode(',', $followedSellers).") THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('created_at', 'desc');

        // تجاهل التحقق من عمود deleted_at
        $productsQuery->whereRaw('1=1');

        return $productsQuery;
    }
}
