<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\CategoryTypeEnum;
use App\Enums\InteractionTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\Exceptions\GraphQLExceptionHandler;
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
        $orderBy = isset($args['order_by']) ?$args['order_by']: ['column' => 'created_at', 'orderBy' => 'desc'];

        $colors = isset($args['colors']) ?$args['colors']: [];
        $type = isset($args['type']) ?$args['type']: null;
        $userId = isset($args['user_id']) ?$args['user_id']: null;
        $sub1Id = isset($args['sub1_id']) ?$args['sub1_id']: null;
        $cityId = isset($args['city_id']) ?$args['city_id']: null;
        $categoryId = isset($args['category_id']) ?$args['category_id']: null;
        $search = isset($args['search']) ?$args['search']: null;
        // throw new GraphQLExceptionHandler($userId);

        return Product::active()
            ->where(function($query)use($colors, $type, $userId, $sub1Id, $cityId, $categoryId, $search,$args){

                if($cityId != null){
                    $query->where(function ($q) use ($cityId) {
                        $q->where('city_id', $cityId)
                            ->orWhereHas('city', fn($q2) => $q2->where('cities.city_id', $cityId));
                    });
                }
                if($type == null && $userId == null && $sub1Id == null){
                    $query->whereNot('type', CategoryTypeEnum::NEWS->value)
                        ->whereNot('type', CategoryTypeEnum::SERVICE->value);
                }
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
                if($type != null){
                    if (isset($args['sub_type']) && !empty($args['sub_type'])) {
                        $query->where('type', $args['sub_type'])->where('end_date', '>', now());
                    } elseif ($type === 'job' || $type === 'search_job') {
                        $query->where(fn($q)=>$q->where('type', 'job')->orWhere('type', 'search_job'))->where('end_date', '>', now());
                    } else {
                        $query->where('type', $type);
                    }

                    if ($userId != null) {
                        $query->where('user_id', '=', $userId);
                    }
                    if($type === 'product' && isset($args['max_price']) && $args['max_price'] > 0){
                        $query->where('price', '>=', [$args['min_price'] ?? 0])->where('price', "<=", $args['max_price'] ?? 10000);
                    }
                    if(collect($colors ?? [])->count() > 0){
                        $query->whereHas('colors', fn($q) => $q->whereIn('colors.id', $colors));
                    }
                }
                if($categoryId != null){
                    $query->where('category_id', $categoryId);
                }
                if($sub1Id != null){
                    $query->where('sub1_id', $sub1Id);
                }
                if(  !empty($search) && $type !== 'seller'){
                    $query->where('name', 'LIKE', "%" . $args['search'] . "%")
                        ->orWhere('expert', 'LIKE', "%" . $args['search'] . "%")
                        ->orWhere('info', 'LIKE', "%" . $args['search'] . "%");
                }
            })
            ->orderBy($orderBy['column'], $orderBy['orderBy']);


        return $products;
    }


}
