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
        $colors = $args['colors'] ?? [];
        $type = $args['type']??null;
        $products = Product::query()->where('active', ProductActiveEnum::ACTIVE->value)
            ->when(isset($args['type']), function ($query) use ($type, $args) {
                if ($type === 'job' || $type === 'search_job') {
                    $query->where('type', 'job')->orWhere('type', 'search_job');
                } elseif ($type === 'seller') {
                    $query->whereHas('user', fn($query) => $query->where('seller_name', 'like', "%" . $args['search'] . "%"));

                } else {
                    $query->where('type', $type);
                }
            })
            ->when($type === 'product' && isset($args['max_price']) && $args['max_price'] > 0, fn($query) => $query->whereBetween('price', [$args['min_price'] ?? 0, $args['max_price']]))
            ->when(count($colors) > 0, fn($query) => $query->whereHas('colors', fn($q) => $q->whereIn('colors.id', $colors)))
            ->when(isset($args['category_id']), fn($query) => $query->where('category_id', $args['category_id']))
            ->when(isset($args['sub1_id']), fn($query) => $query->where('sub1_id', $args['sub1_id']))
            ->when(isset($args['city_id']), fn($query) => $query->where('city_id', $args['city_id']))
            ->when(isset($args['user_id']), fn($query) => $query->where('user_id', $args['user_id']))
            ->when(isset($args['search']) && !empty($args['search']) && $type !== 'seller', fn($query) => $query->where(function ($query) use ($args) {
                /*  $query->where('name','Like',"%{$args['search']}%");
                  $query->orWhere('expert','Like',"%{$args['search']}%");
                  $query->orWhere('info','Like',"%{$args['search']}%");*/
                /**
                 * @var $searchTerms array<string>
                 */
                $searchTerms = explode(' ', $args['search']); // تحويل البحث إلى مصفوفة كلمات
                /**
                 * @var $term string
                 */
                $term = '';
                foreach ($searchTerms as $term) {
                    $query->orWhere(function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%$term%")
                            ->orWhere('expert', 'LIKE', "%$term%")
                            ->orWhere('info', 'LIKE', "%$term%");
                    });
                }

            }))
            ->inRandomOrder()
            ->orderBy('level')->orderBy('created_at');

        $ids = $products->paginate($args['first'] ?? 15, ['*'], 'page', $args['page'] ?? 1)->pluck('id')->toArray();
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


        return $products;
    }
}
