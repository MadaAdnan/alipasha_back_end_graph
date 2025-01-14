<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use DB;

final class HomeProduct
{
    public function __invoke($_, array $args)
    {
        $userCategoryIds=[];
        if(auth()->check()){
            $userCategoryIds =Interaction::where('user_id', auth()->id())->whereNotNull('category_id')
                ->groupBy('category_id')
                ->orderByRaw('SUM(visited) DESC')
                ->pluck('category_id')->toArray();;
        }

        $featuredProducts = Product::select(
            'products.*',
            DB::raw("'featured' as t")
        )
            ->where('level', \App\Enums\LevelProductEnum::SPECIAL->value)
            ->limit(15);

// المنتجات الجديدة (10 منتجات)
        $newProducts = Product::whereDate('created_at', '>=', now()->subDays(30))
            ->select(
                'products.*',
                DB::raw("'new' as t")
            )
            ->limit(10);

// المنتجات الخاصة بالأقسام التي يتابعها المستخدم (15 منتج)
        $categoryProducts = Product::whereIn('category_id', $userCategoryIds)
            ->select(
                'products.*',
                DB::raw("'category' as t")
            )
            ->limit(15);

// دمج جميع النتائج
        $products = $featuredProducts
            ->unionAll($newProducts)
            ->unionAll($categoryProducts)
            ->unionAll(
            // استعلام المنتجات العامة (لإكمال باقي النتائج)
                Product::select(
                    'products.*',
                    DB::raw("'general' as t")
                )
                    ->whereNotIn('id', function ($query) use ($userCategoryIds) {
                        // استبعاد المنتجات التي تم تضمينها سابقًا
                        $query->select('id')
                            ->from('products')
                            ->where('level', \App\Enums\LevelProductEnum::SPECIAL->value)
                            ->orWhereDate('created_at', '>=', now()->subDays(30))
                            ->orWhereIn('category_id', $userCategoryIds);
                    })
            )
            ->orderBy('created_at', 'desc');
        return $products;
        $ids = $products->pluck('id')->toArray();
        $today = today();

        \DB::transaction(function () use ($ids, $today) {

            // تحديث السجلات الموجودة
            \DB::table('product_views')
                ->whereIn('product_id', $ids)
                ->whereDate('view_at', $today)
                ->update(['count' => \DB::raw('count + 1')]);
            $existingIds = \DB::table('product_views')
                ->whereIn('product_id', $ids)
                ->whereDate('view_at', $today)
                ->pluck('product_id')
                ->toArray();
            $newIds = array_diff($ids, $existingIds);
            if (
                !empty($newIds)) {
                $inserts = array_map(function ($id) use ($today) {
                    return [
                        'product_id' => $id,
                        'view_at' => $today,
                        'count' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $newIds);

                \DB::table('product_views')->insert($inserts);
            }
        });
        return $products;
    }


}
