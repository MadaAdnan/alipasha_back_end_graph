<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\ProductActiveEnum;
use App\Models\Product;
use App\Models\ProductView;
use Carbon\Carbon;

final class Products
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)

    {
        $products = Product::query()->where('active',ProductActiveEnum::ACTIVE->value)
            ->when(isset($args['type']), fn($query) => $query->where('type', $args['type']))
            ->when(isset($args['category_id']), fn($query) => $query->where('category_id', $args['category_id']))
            ->when(isset($args['sub1_id']), fn($query) => $query->where('sub1_id', $args['sub1_id']))
            ->when(isset($args['city_id']), fn($query) => $query->where('city_id', $args['city_id']))
            ->when(isset($args['user_id']), fn($query) => $query->where('user_id', $args['user_id']))
            ->when(isset($args['search']), fn($query) => $query->search($args['search']))->latest();

        $ids = $products->paginate($args['first'] ?? 15, ['*'], 'page', $args['page'] ?? 1)->pluck('id')->toArray();
        $today = today();
          \DB::transaction(function() use ($ids,$today) {

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
                  $inserts = array_map(function($id) use ($today) {
                      return [
                          'product_id' => $id,
                          'view_at' => $today,
                          'count' => 1,
                          'created_at'=>now(),
                          'updated_at'=>now(),
                      ];
                  }, $newIds);

                  \DB::table('product_views')->insert($inserts);
              }
          });



        return $products;
    }
}
