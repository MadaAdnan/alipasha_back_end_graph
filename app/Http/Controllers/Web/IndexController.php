<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\LevelUserEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Community;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting=Setting::first();
        $categoryId = \request()->get('category_id');
        $specialSeller = User::where([
            'level' => LevelUserEnum::SELLER->value,
            'is_special' => true,
            'is_active' => true,
        ])->get();
        $notifications = null;
        if (auth()->check()) {
            // unreadNotifications
            $notifications = auth()->user()->unreadNotifications()->limit(7)->get();
            auth()->user()->unreadNotifications->markAsRead();
        }
        // getSpecialProduct
        $specials= Product::where(['active'=>ProductActiveEnum::ACTIVE->value,
            'level'=>LevelProductEnum::SPECIAL->value])

            ->where(fn( $query)=>$query->whereDoesntHave('category',fn($query)=>$query->where('type',CategoryTypeEnum::RESTAURANT->value)))

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
            })->inRandomOrder()
            ->where('created_at','>=',now()->subDays($setting->social['recommended_month']??30))
           ->paginate(5);
        ///
        $countLatest=15;
        $count=15;
        if($specials->count()==0){
            $count=17;
            $countLatest=18;
        }


        $hobbies = Product::when($categoryId, fn($query) => $query->where('category_id', $categoryId))
            ->where('active', ProductActiveEnum::ACTIVE->value)
            ->where(function ($query) {
                $query->where('type', CategoryTypeEnum::PRODUCT->value)
                    ->orWhere('type', CategoryTypeEnum::JOB->value)
                    ->orWhere('type', CategoryTypeEnum::SEARCH_JOB->value)
                    ->orWhere('type', CategoryTypeEnum::NEWS->value)
                    ->orWhere('type', CategoryTypeEnum::TENDER->value);
        })  ->when(auth()->check(),fn($query)=>$query->where(fn($q)=>
            $q->whereIn('category_id',$this->getPopularCategoryProducts())
                ->orWhereIn('user_id',$this->getPopularSelelrProducts())
            ))->inRandomOrder()->where('created_at','>=',now()->subDays($setting->social['recommended_month']??30))
            ->paginate($count);
        $latests= Product::
        where(fn( $query)=>$query->where('active',ProductActiveEnum::ACTIVE->value)->whereDoesntHave('category',fn($query)=>$query->where('type',CategoryTypeEnum::RESTAURANT->value)))
            ->whereNot('level',LevelProductEnum::SPECIAL->value)
            ->where(fn($query)=> $query
                ->where('type',CategoryTypeEnum::PRODUCT->value)
                ->orWhere('type',CategoryTypeEnum::TENDER->value)
                ->orWhere('type',CategoryTypeEnum::JOB->value)
                ->orWhere('type',CategoryTypeEnum::SEARCH_JOB->value)
                ->orWhere('type',CategoryTypeEnum::NEWS->value)
            )  ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            })->where('created_at','>=',now()->subDays($setting->social['recommended_month']??30))->inRandomOrder()
            ->when(auth()->check(),fn($query)=>$query->where(fn($q)=>
            $q->whereNotIn('category_id',$this->getPopularCategoryProducts())
                ->whereNotIn('user_id',$this->getPopularSelelrProducts())
            ))

            ->paginate($countLatest);

        $subCategory = Category::whereHas('parents', fn($query) => $query->where('category_id', $categoryId))->get();
        $categories = Category::where('is_active', true)
            ->where(fn($query) => $query->where('type', CategoryTypeEnum::PRODUCT->value)->orWhere('type', CategoryTypeEnum::RESTAURANT->value))->orderBy('sortable')->get();


        $ids = array_merge($latests->pluck('id')->toArray(),$hobbies->pluck('id')->toArray(),$specials->pluck('id')->toArray());

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
        $communities = null;
        if (auth()->check()) {
            $communities = Community::whereNot('type', 'live')->whereHas('messages')->whereHas('allUsers', function ($query) {
                $query->where('users.id', auth()->id());  // جلب المجتمعات التي يشارك فيها المستخدم الحالي
            })->latest('last_update')->limit(10)->get();
        }

        return view('web.index', compact('specialSeller', 'latests','hobbies','specials', 'categories', 'subCategory', 'communities', 'notifications'));
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
