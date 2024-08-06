<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\ProductActiveEnum;
use App\Models\ProductView;
use Carbon\Carbon;

final class Product
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $proudct = \App\Models\Product::findOrFail($args['id']);
        ProductView::updateOrCreate(['view_at' => Carbon::today(), 'product_id' => $proudct->id], // الشروط التي يجب أن تتطابق
            ['count' => \DB::raw('count + 1')]);

        $products= \App\Models\Product::query()->where('active', ProductActiveEnum::ACTIVE->value)
            ->where('category_id',$proudct->category_id)
            ->where('sub1_id',$proudct->sub1_id)->inRandomOrder()->latest()->take(6)->get();
        $ids = $products->pluck('id')->toArray();
        $today = today();
        \DB::transaction(function () use ($ids, $today) {

            // تحديث السجلات الموجودة
            \DB::table('product_views')
                ->whereIn('product_id', $ids)
                ->whereDate('view_at', $today)
                ->update(['count' => \DB::raw('count + 1')]);

            // إدخال السجلات الجديدة
            $existingIds = \DB::table('product_views')
                ->whereIn('product_id', $ids)
                ->whereDate('view_at', $today)
                ->pluck('product_id')
                ->toArray();

            $newIds = array_diff($ids, $existingIds);

            if (!empty($newIds)) {
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


        return [
            "product"=>$proudct,
            "products"=>$products
        ];
    }
}
