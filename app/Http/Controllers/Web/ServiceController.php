<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $city = \request()->get('city');

        $category = \request()->get('category');
        $q = \request()->get('q');
        $cities = City::where('is_active', true)->where('is_main', true)->get();
        $services_count = Product::service()
            ->where('active', ProductActiveEnum::ACTIVE->value)->count();
        $views = ProductView::whereHas('product', fn($query) => $query->where('type', CategoryTypeEnum::SERVICE->value))->sum('count');
        $sellers = Product::where('type',CategoryTypeEnum::SERVICE->value)->select('user_id')->groupBy('user_id')->count();
        $categories = Category::whereHas('parents', fn($query) => $query->where('type', CategoryTypeEnum::SERVICE->value))->whereHas('products2')
            ->where('is_active',1)->get();
        $services = Product::service()->where('active', ProductActiveEnum::ACTIVE->value)
            ->whereHas('category',fn($q)=>$q->where('is_active',1))
            ->whereHas('sub1',fn($q)=>$q->where('is_active',1))
            ->when(!empty($q), fn($query) => $query->where('info', 'like', "%{$q}%"))
            ->when(!empty($city), fn($query) => $query->where('city_id', $city))

            ->when(!empty($category), fn($query) => $query->where('sub1_id', $category))
            ->latest()->paginate(10);

        return view('web.services', compact('cities', 'services', 'services_count', 'views', 'sellers', 'categories'));
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
        $service=Product::service()->findOrFail($id);
        $ids = [$service->id];
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
      //  $categories = Category::whereHas('parents', fn($query) => $query->where('type', CategoryTypeEnum::SERVICE->value))->whereHas('products2')->get();
        return view('web.service-item',compact('service',));
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
