<?php declare(strict_types=1);

namespace App\GraphQL\Queries\HomeQuery;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Interaction;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final class HobbiesProduct
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $products = Product::/*where('id','<',0)->*/
            where(fn( $query)=>$query->where('active', ProductActiveEnum::ACTIVE->value)
            ->whereDoesntHave('category',fn($query)=>$query->where('type',CategoryTypeEnum::RESTAURANT->value)))

            ->where(fn($query)=> $query
                ->where('type',CategoryTypeEnum::PRODUCT->value)
                ->orWhere('type',CategoryTypeEnum::TENDER->value)
                ->orWhere('type',CategoryTypeEnum::JOB->value)
                ->orWhere('type',CategoryTypeEnum::SEARCH_JOB->value)
                ->orWhere('type',CategoryTypeEnum::NEWS->value)
            )
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })
          /*  ->whereIn('category_id', $this->getPopularCategoryProducts())
            ->orWhere('user_id',$this->getPopularSelelrProducts())*/
            ->orderByDesc('created_at');
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

    private function getPopularCategoryProducts()
    {
        return Interaction::where('user_id', auth()->id())->whereNotNull('category_id')
            ->latest()
            ->groupBy('category_id')
            ->orderByRaw('SUM(visited) DESC')
            ->pluck('category_id')->toArray();
    }
    private function getPopularSelelrProducts()
    {
        return Interaction::where('user_id', auth()->id())->whereNotNull('seller_id')
            ->groupBy('seller_id')

            ->pluck('seller_id')->toArray();
    }
}
