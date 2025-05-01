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

        $colors = isset($args['colors']) ?: [];
        $type = isset($args['type'])?: null;
        $userId = isset($args['user_id']) ?: null;
        $sub1Id = isset($args['sub1_id']) ?: null;
        $cityId = isset($args['city_id']) ?: null;
        $categoryId = isset($args['category_id']) ?: null;

        return Product::query()->where('active', ProductActiveEnum::ACTIVE->value)
            ->when($type == null && $userId == null && $sub1Id == null, fn($query) => $query->whereNot('type', CategoryTypeEnum::NEWS->value)->whereNot('type', CategoryTypeEnum::SERVICE->value))
            ->where('active', ProductActiveEnum::ACTIVE->value)
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->when($type!=null, function ($query) use ($type, $args) {
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

            // ->whereNotNull('sub1_id')
            ->orderBy($orderBy['column'], $orderBy['orderBy']);


        return $products;
    }


}
