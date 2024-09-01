<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Exceptions\GraphQLExceptionHandler;
use App\Models\Slider;

final class Sliders
{
    /**
     * @param null $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $category_id = $args['category_id']??null;
        $user_id = $args['user_id']??null;
        $city_id = $args['city_id']??null;
        $type = $args['type']??null;

        $sliders = Slider::
        when($category_id != null, fn($query) => $query->where('category_id', $category_id))
            ->when($user_id != null, fn($query) => $query->where('user_id', $user_id))
            ->when($city_id != null, fn($query) => $query->where('city_id', $city_id))
            ->when($type != null, fn($query) => $query->whereHas('category', fn($query) => $query->where('categories.type', $type)))
            ->with(['category', 'user'])->get();

        $ids = $sliders->pluck('id')->toArray();
        $today = today();
       try{
           \DB::transaction(function () use ($ids, $today) {

               // تحديث السجلات الموجودة
               \DB::table('slider_views')
                   ->whereIn('slider_id', $ids)
                   ->whereDate('view_at', $today)
                   ->update(['count' => \DB::raw('count + 1')]);

               // إدخال السجلات الجديدة
               $existingIds = \DB::table('slider_views')
                   ->whereIn('slider_id', $ids)
                   ->whereDate('view_at', $today)
                   ->pluck('slider_id')
                   ->toArray();

               $newIds = array_diff($ids, $existingIds);

               if (!empty($newIds)) {
                   $inserts = array_map(function ($id) use ($today) {
                       return [
                           'slider_id' => $id,
                           'view_at' => $today,
                           'count' => 1,
                           'created_at' => now(),
                           'updated_at' => now(),
                       ];
                   }, $newIds);

                   \DB::table('slider_views')->insert($inserts);
               }
           });
       }catch (\Exception | \Error $e){
           throw new GraphQLExceptionHandler($e->getMessage());
       }


        return $sliders;
    }
}
