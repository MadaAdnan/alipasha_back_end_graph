<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\CategoryTypeEnum;
use App\Enums\InteractionTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Models\Category;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\ProductView;
use Cache;
use Carbon\Carbon;

final class Products
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)

    {
        $orderBy = $args['order_by'] ?? ['column' => 'created_at', 'orderBy' => 'desc'];

        $colors = $args['colors'] ?? [];
        $type = $args['type'] ?? null;
$userId=$args['user_id']??null;
$sub1Id=$args['sub1_id']??null;

        return Product::query()
            ->when($type==null && $userId==null && $sub1Id==null, fn($query) => $query->whereNot('type', CategoryTypeEnum::NEWS->value)->whereNot('type', CategoryTypeEnum::SERVICE->value))
            ->where('active', ProductActiveEnum::ACTIVE->value)
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->when(isset($args['type']), function ($query) use ($type, $args) {
                if (isset($args['sub_type']) && !empty($args['sub_type'])) {
                    $query->where('type', $args['sub_type'])->where('end_date', '>', now());
                } elseif ($type === 'job' || $type === 'search_job') {
                    $query->where('type', 'job')->orWhere('type', 'search_job')->where('end_date', '>', now());
                } /*elseif ($type === 'seller') {
                    $query->whereHas('user', fn($query) => $query->where('seller_name', 'like', "%" . $args['search'] . "%"));

                }*/ else {
                    $query->where('type', $type);
                }
            })
            ->when($type === 'product' && isset($args['max_price']) && $args['max_price'] > 0, fn($query) => $query->where('price', '>=', [$args['min_price'] ?? 0])->where('price', "<=", $args['max_price'] ?? 10000))
            ->when(collect($colors ?? [])->count() > 0, fn($query) => $query->whereHas('colors', fn($q) => $q->whereIn('colors.id', $colors)))
            ->when(isset($args['category_id']), fn($query) => $query->where('category_id', $args['category_id']))
            ->when(isset($args['sub1_id']), fn($query) => $query->where('sub1_id', $args['sub1_id']))
            ->when(isset($args['city_id']), fn($query) => $query->where('city_id', $args['city_id']))
            ->when(isset($args['user_id']), fn($query) => $query->where('user_id', $args['user_id']))
            ->when(isset($args['search']) && !empty($args['search']) && $type !== 'seller', fn($query) => $query->where(function ($query) use ($args) {

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
            // ->whereNotNull('sub1_id')
            ->orderBy($orderBy['column'], $orderBy['orderBy']);


        return $products;
    }


}
