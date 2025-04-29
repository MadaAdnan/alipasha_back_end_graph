<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

final class HomeProduct
{
    public function __invoke($_, array $args)
    {
      /*  $userCategoryIds=[];
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
        return $products;*/
        $page = $args['page']??1;
        $perPage = 50;
        $skip = ($page - 1) * $perPage;

        $isAuthenticated = auth()->check();

        if ($isAuthenticated) {
            $userId = auth()->id();

            // النسب
            $featuredCount = floor($perPage * 0.2);     // 20%
            $interestedCount = floor($perPage * 0.6);   // 60%
            $otherCount = $perPage - ($featuredCount + $interestedCount);

            // الأقسام المهتم بها المستخدم
            $interestedCategoryIds = DB::table('interactions')
                ->where('user_id', $userId)
                ->select('category_id')
                ->groupBy('category_id')
                ->orderByRaw('COUNT(*) DESC')
                ->pluck('category_id')
                ->toArray();

            // المنتجات المميزة
            $featuredProducts = Product::where('level', 'مميز')
                ->orderBy('created_at', 'desc')
                ->limit((int)$featuredCount)
                ->get();

            // المنتجات من الاهتمامات
            $interestedProducts = Product::whereIn('category_id', $interestedCategoryIds)
                ->whereNotIn('id', $featuredProducts->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->limit((int)$interestedCount)
                ->get();

            // باقي المنتجات لتعويض النقص
            $excludedIds = $featuredProducts->pluck('id')
                ->merge($interestedProducts->pluck('id'))
                ->toArray();

            $remainingCount = $perPage - (count($featuredProducts) + count($interestedProducts));

            $otherProducts = Product::whereNotIn('id', $excludedIds)
                ->orderBy('created_at', 'desc')
                ->limit($remainingCount)
                ->get();

            $products = $featuredProducts
                ->merge($interestedProducts)
                ->merge($otherProducts);

        } else {
            // الزائر غير مسجل
            $half = floor($perPage / 2);

            $featuredProducts = Product::where('level', 'مميز')
                ->orderBy('created_at', 'desc')
                ->limit((int)$half)
                ->get();

            $excludedIds = $featuredProducts->pluck('id')->toArray();

            $otherProducts = Product::whereNotIn('id', $excludedIds)
                ->orderBy('created_at', 'desc')
                ->limit($perPage - count($featuredProducts))
                ->get();

            $products = $featuredProducts->merge($otherProducts);
        }

// بناء paginator يدوي


        $paginator = new LengthAwarePaginator(
            $products,
            Product::count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginator;
    }


}
