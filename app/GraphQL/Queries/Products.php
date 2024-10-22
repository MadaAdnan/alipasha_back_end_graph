<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Enums\InteractionTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
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
       // $orderBy=$args['orderBy']??['end_date','desc'];
        $colors = $args['colors'] ?? [];
        $type = $args['type'] ?? null;
        if (auth()->check()) {
            $products = $this->getSuggestProducts($type, $args, $colors);
            // $products = $this->suggestProducts($type, $args, $colors);
        } else {
            $products = $this->getProducts($type, $args, $colors);
        }

        $ids = $products ->when(isset($args['orderBy']),fn($query)=>$query->orderBy($args['orderBy']),fn($query)=>$query->orderBy('created_at','desc') ->orderBy('level'))->paginate($args['first'] ?? 15, ['*'], 'page', $args['page'] ?? 1)->pluck('id')->toArray();
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

    private function getProducts($type, $args, $colors)
    {



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
            ->when($type === 'product' && isset($args['max_price']) && $args['max_price'] > 0, fn($query) => $query->where('price', '>=', [$args['min_price'] ?? 0])->where('price', "<=", $args['max_price'] ?? 10000))
            ->when(collect($colors ?? [])->count() > 0, fn($query) => $query->whereHas('colors', fn($q) => $q->whereIn('colors.id', $colors)))
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
         //   ->inRandomOrder()
            ->when(isset($args['orderBy']),fn($query)=>$query->orderBy($args['orderBy']),fn($query)=>$query->orderBy('created_at','desc') ->orderBy('level'));
        return $products;
    }


    private function getPopularCategoryProducts()
    {
        return Interaction::whereNotNull('category_id')
            ->groupBy('category_id')
            ->orderByRaw('SUM(visited) DESC')
            ->value('category_id');
    }



    private function getFollowedStoreProducts($userId)
    {
        return Interaction::where('user_id', $userId)->whereNotNull('seller_id')

            ->pluck('seller_id');
    }

    private function getSuggestProducts($type, $args, $colors)
    {
        // جلب القسم الأكثر زيارة
        $popularCategory = $this->getPopularCategoryProducts();


        // جلب المتاجر التي تم متابعتها من قبل المستخدم
        $followedStores =  $this->getFollowedStoreProducts(auth()->id())->toArray();


        $products = Product::query()
            ->where('active', ProductActiveEnum::ACTIVE->value)
            ->when(isset($args['type']), function ($query) use ($type, $args) {
                if ($type === 'job' || $type === 'search_job') {
                    $query->where('products.type', 'job')->orWhere('products.type', 'search_job');
                } elseif ($type === 'seller') {
                    $query->whereHas('user', fn($query) => $query->where('seller_name', 'like', "%" . $args['search'] . "%"));
                } else {
                    $query->where('products.type', $type);
                }
            })
            ->when($type === 'product' && isset($args['max_price']) && $args['max_price'] > 0, fn($query) => $query->where('price', '>=', [$args['min_price'] ?? 0])->where('price', "<=", $args['max_price'] ?? 10000))
            ->when(collect($colors??[])->count() > 0, fn($query) => $query->whereHas('colors', fn($q) => $q->whereIn('colors.id', $colors)))
            ->when(isset($args['category_id']), fn($query) => $query->where('products.category_id', $args['category_id']))
            ->when(isset($args['sub1_id']), fn($query) => $query->where('sub1_id', $args['sub1_id']))
            ->when(isset($args['city_id']), fn($query) => $query->where('city_id', $args['city_id']))
            ->when(isset($args['user_id']), fn($query) => $query->where('products.user_id', $args['user_id']))
            ->when(isset($args['search']) && !empty($args['search']) && $type !== 'seller', fn($query) => $query->where(function ($query) use ($args) {
                $searchTerms = explode(' ', $args['search']); // تحويل البحث إلى مصفوفة كلمات
                $term=null;
                foreach ($searchTerms as $term) {
                    $query->orWhere(function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%$term%")
                            ->orWhere('expert', 'LIKE', "%$term%")
                            ->orWhere('info', 'LIKE', "%$term%");
                    });
                }
            }))
            ->when($popularCategory || !empty($followedStores), function ($query) use ($popularCategory, $followedStores) {
                // إعطاء الأولوية للأقسام التي تم زيارتها أو التعليق عليها والمتاجر التي تم متابعتها
                $query->orderByRaw(
                    "CASE
                    WHEN products.level = ? THEN 1
                    WHEN products.sub1_id = ? THEN 2

                    WHEN products.user_id IN (?) THEN 3
                    ELSE 4
                END ASC", [LevelProductEnum::SPECIAL->value,$popularCategory,implode(',', $followedStores)]
                );
            })
            ->groupBy('products.id') // نضمن أن المنتجات يتم جمعها وتجنب التكرار
            ->orderByRaw(
                "CASE
    WHEN products.level = ? THEN 1
    WHEN products.sub1_id = ? THEN 2
    WHEN products.user_id IN (?) THEN 3
    ELSE 4
    END ASC", [LevelProductEnum::SPECIAL->value, $popularCategory, implode(',', $followedStores)]
            );
           // ->orderByRaw('RAND()') // ترتيب عشوائي للمنتجات مع نفس مستوى التفاعل
//            ->orderBy('level')
           /* ->when(isset($args['orderBy']),fn($query)=>$query->orderBy($args['orderBy']),fn($query)=>$query->orderBy('created_at','desc') ->orderBy('level'));*/

        return $products;
    }


}
