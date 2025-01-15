<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\ProductActiveEnum;
use App\Models\Advice;

final class Advices
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $advices = Advice::where('advices..status', ProductActiveEnum::ACTIVE->value)
            ->where('expired_date', ">=", now())
            ->when(isset($args['category_id']) && !empty($args['category_id']), fn($query) => $query->where('category_id', $args['category_id']),fn($query)=>$query->whereNull('category_id'))
            ->when(isset($args['user_id']) && !empty($args['user_id']), fn($query) => $query->where('user_id', $args['user_id']))
            ->when(isset($args['city_id']) && !empty($args['city_id']), fn($query) => $query->where('city_id', $args['city_id']))
            ->inRandomOrder()->get();
        $ids = $advices->pluck('id')->toArray();
        $today = today();
        \DB::transaction(function () use ($ids, $today) {

            // تحديث السجلات الموجودة
            \DB::table('advice_views')
                ->whereIn('advice_id', $ids)
                ->whereDate('view_at', $today)
                ->update(['count' => \DB::raw('count + 1')]);

            // إدخال السجلات الجديدة
            $existingIds = \DB::table('advice_views')
                ->whereIn('advice_id', $ids)
                ->whereDate('view_at', $today)
                ->pluck('advice_id')
                ->toArray();

            $newIds = array_diff($ids, $existingIds);

            if (!empty($newIds)) {
                $inserts = array_map(function ($id) use ($today) {
                    return [
                        'advice_id' => $id,
                        'view_at' => $today,
                        'count' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $newIds);

                \DB::table('advice_views')->insert($inserts);
            }
        });

        return $advices;
    }
}
