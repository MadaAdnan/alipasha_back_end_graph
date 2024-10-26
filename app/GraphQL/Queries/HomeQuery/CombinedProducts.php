<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;

final class CombinedProducts
{
    public function __invoke($_, array $args)
    {
        $hobbiesProducts=[];
        // اجلب المنتجات من الاستعلامات الثلاثة
        if(auth()->check()){
            $hobbiesProducts = Product::where('active', ProductActiveEnum::ACTIVE->value)
                ->whereIn('category_id', $this->getPopularCategoryProducts())
                ->inRandomOrder()
                ->get();
        }


        $latestProducts = Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->whereNot('level', LevelProductEnum::SPECIAL->value)
            ->latest()
            ->inRandomOrder()
            ->get();

        $specialProducts = Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->where('level', LevelProductEnum::SPECIAL->value)
            ->get();

        // دمج النتائج الثلاثة
        $combinedProducts = $hobbiesProducts
            ->concat($latestProducts)
            ->concat($specialProducts);

        // إزالة التكرار حسب الـ ID
        $uniqueProducts = $combinedProducts->unique('id')->values();

        // تطبيق pagination
        $page = $args['page'] ?? 1;
        $perPage = $args['perPage'] ?? 15; // عدد العناصر في الصفحة الواحدة
        $paginatedProducts = $this->paginate($uniqueProducts, $perPage, $page);

        return $paginatedProducts;
    }

    private function getPopularCategoryProducts()
    {
        return Interaction::where('user_id', auth()->id())
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderByRaw('SUM(visited) DESC')
            ->pluck('category_id')
            ->toArray();
    }

    /**
     * دالة لتطبيق الـ pagination بشكل يدوي
     */
    private function paginate($items, $perPage, $page, $options = [])
    {
        $offset = ($page - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage);

        return new LengthAwarePaginator($paginatedItems, $items->count(), $perPage, $page, $options);
    }
}
