<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelUserEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Community;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryId = \request()->get('category_id');
        $specialSeller = User::where([
            'level' => LevelUserEnum::SELLER->value,
            'is_special' => true,
            'is_active' => true,
        ])->get();
        $notifications=null;
        if(auth()->check()){
            $notifications=auth()->user()->notifications()->limit(7)->get();
            auth()->user()->unreadNotifications->markAsRead();
        }
        $products = Product::when($categoryId,fn($query)=>$query->where('category_id',$categoryId))
        ->where(function ($query) {
            $query->where('active', ProductActiveEnum::ACTIVE->value);
            $query->where(fn($query) => $query->where('type', CategoryTypeEnum::PRODUCT->value)
                ->orWhere('type', CategoryTypeEnum::JOB->value)
                ->orWhere('type', CategoryTypeEnum::SEARCH_JOB->value)
                ->orWhere('type', CategoryTypeEnum::NEWS->value)
                ->orWhere('type', CategoryTypeEnum::TENDER->value)
            );
        })->latest()->paginate(35);
        $subCategory = Category::whereHas('parents', fn($query) => $query->where('category_id', $categoryId))->get();
        $categories = Category::where('is_active', true)
            ->where(fn($query) => $query->where('type', CategoryTypeEnum::PRODUCT->value)->orWhere('type', CategoryTypeEnum::RESTAURANT->value))->orderBy('sortable')->get();


        $ids = $products->pluck('id')->toArray();
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
$communities=null;
if(auth()->check()){
    $communities=Community::whereNot('type', 'live')->whereHas('messages')->whereHas('allUsers', function ($query) {
        $query->where('users.id', auth()->id());  // جلب المجتمعات التي يشارك فيها المستخدم الحالي
    })->latest('last_update')->limit(10)->get();
}
        return view('web.index', compact('specialSeller', 'products', 'categories','subCategory','communities','notifications'));
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
