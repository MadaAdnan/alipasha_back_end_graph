<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use Auth;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

final class HomeProduct
{
    public function __invoke($_, array $args)
    {
    /*   $userCategoryIds = [];
        if (auth()->check()) {
            $userCategoryIds = Interaction::where('user_id', auth()->id())->whereNotNull('category_id')
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

        $page = $args['page'] ?? 1;
        $perPage = $args['perPage'] ?? 50;

        $user = Auth::user();
        $isAuthenticated = $user !== null;

        $products = collect();

        if ($isAuthenticated) {
            $featuredCount = floor($perPage * 0.2);
            $interestCount = floor($perPage * 0.6);
            $remainingCount = $perPage - ($featuredCount + $interestCount);

            $interestedCategoryIds = DB::table('interactions')
                ->where('user_id', $user->id)
                ->select('category_id')
                ->groupBy('category_id')
                ->orderByRaw('COUNT(*) DESC')
                ->pluck('category_id')
                ->toArray();

            $featured = Product::where('level',  LevelProductEnum::SPECIAL->value)
                ->where('active',ProductActiveEnum::ACTIVE->value)
                ->orderBy('created_at', 'desc')
                ->limit((int)$featuredCount)
                ->get();

            $interested = Product::whereIn('category_id', $interestedCategoryIds)
                ->whereNotIn('id', $featured->pluck('id'))
                ->where('active',ProductActiveEnum::ACTIVE->value)
                ->orderBy('created_at', 'desc')
                ->limit((int)$interestCount)
                ->get();

            $excludedIds = $featured->pluck('id')
                ->merge($interested->pluck('id'))
                ->toArray();

            $others = Product::whereNotIn('id', $excludedIds)
                ->where('active',ProductActiveEnum::ACTIVE->value)
                ->orderBy('created_at', 'desc')
                ->limit($perPage - count($featured) - count($interested))
                ->get();

            $products = $featured->merge($interested)->merge($others);
        } else {
            $half = floor($perPage / 2);

            $featured = Product::where('level', LevelProductEnum::SPECIAL->value)
                ->where('active',ProductActiveEnum::ACTIVE->value)
                ->orderBy('created_at', 'desc')
                ->limit((int)$half)
                ->get();

            $others = Product::whereNotIn('id', $featured->pluck('id'))
                ->where('active',ProductActiveEnum::ACTIVE->value)
                ->orderBy('created_at', 'desc')
                ->limit($perPage - count($featured))
                ->get();

            $products = $featured->merge($others);
        }

        // عمل pagination يدوي
        $paginated = new LengthAwarePaginator(
            $products,
            Product::count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return [
            'data' => $paginated->items(),
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
        ];
    }

}
