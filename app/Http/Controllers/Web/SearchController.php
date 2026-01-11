<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type = \request()->get('type') ?? 'product';
        $text = \request()->get('q');
        $city = \request()->get('city_id') ?? \request()->get('city');
        $area = \request()->get('area_id');

        $category = \request()->get('category');
        $section = \request()->get('section');
        $priceFrom = \request()->get('price_from') ?? 0;
        $priceTo = \request()->get('price_to');

        $products = Product::where('active', ProductActiveEnum::ACTIVE->value)
            ->when(!empty($type), function ($query) use ($type) {
                if ($type == CategoryTypeEnum::SEARCH_JOB->value || $type == CategoryTypeEnum::JOB->value) {
                    $query->whereIn('type', [
                        CategoryTypeEnum::SEARCH_JOB->value,
                        CategoryTypeEnum::JOB->value,
                    ]);
                } else {
                    $query->where('type', $type);
                }
            })
            ->when(!empty($text), fn($query) => $query->where(fn($q) => $q->where('name', 'like', "%{$text}%")->orWhere('info', 'like', "%{$text}%")))
            ->when(!empty($city), fn($query) => $query->where('city_id', $city))
            ->when(!empty($area), fn($query) => $query->whereHas('user', fn($query) => $query->where('area_id', $area)))
            ->when(!empty($category), fn($query) => $query->where('sub1_id', $category))
            ->when(!empty($section), fn($query) => $query->where('category_id', $section))
            ->where('price', '>=', $priceFrom)
            ->when(!empty($priceTo), fn($query) => $query->where('price', '<=', $priceTo))
            ->latest()
            ->simplePaginate();
        dd(get_class($products));
        $categories = Category::where('is_active', true)
            ->where(['is_active' => true, 'is_main' => true])
            ->whereIn('type', [
                CategoryTypeEnum::PRODUCT->value,
                CategoryTypeEnum::JOB->value,
                CategoryTypeEnum::SEARCH_JOB->value,
//                CategoryTypeEnum::TENDER->value,
//                CategoryTypeEnum::NEWS->value,
            ])
            ->orderBy('sortable')
            ->orderByRaw("FIELD(type, 'product', 'job', 'search_job','tender','service','news')")
            ->get();
        return view('theme2.search', compact('products',  'categories'));
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
