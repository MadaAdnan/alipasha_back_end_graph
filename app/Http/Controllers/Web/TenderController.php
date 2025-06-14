<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;

class TenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $city=\request()->get('town');

        $category=\request()->get('category_id');
        $sub=\request()->get('sub_id');
        $q=\request()->get('q');
        $cities=City::where('is_active',true)->where('is_main',true)->get();
        $tender_count = Product::tender()
            ->where('active', ProductActiveEnum::ACTIVE->value)/*->where('end_date','>',now())*/->count();
        $views = ProductView::whereHas('product', fn($query) => $query->tender())->sum('count');
        $sellers = Product::tender()->select('user_id')->groupBy('user_id')->count();
        $categories=Category::tender()->where('is_active',1)->where('is_main',true)->get();
        $tenders=Product::tender()
           /* ->where('end_date','>',now())*/
            ->when(!empty($q),fn($query)=>$query->where('info','like',"%{$q}%"))
            ->where('active',ProductActiveEnum::ACTIVE->value)
            ->when(!empty($city),fn($query)=>$query->where('city_id',$city))

            ->when(!empty($category),fn($query)=>$query->where('category_id',$category))
            ->when(!empty($sub),fn($query)=>$query->where('sub1_id',$sub))
            ->latest()->paginate(10);
        return view('web.tenders',compact('tenders','cities','tender_count','views','sellers','categories'));
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
        $tender=Product::tender()->findOrFail($id);
        $ids = [$tender->id];
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
        return view('web.tender-item',compact('tender'));
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
