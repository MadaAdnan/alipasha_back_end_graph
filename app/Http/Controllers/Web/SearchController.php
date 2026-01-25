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
        $type = \request()->filled('type')?\request()->get('type') : 'product';
        $text = \request()->get('q');
        $city = \request()->get('city_id') ?? \request()->get('city');
        $area = \request()->get('area_id');

        $category = \request()->get('category_id');
        $seller = \request()->get('seller_id');

        $priceFrom = \request()->filled('price_from') ?\request()->get('price_from'):0;
        $priceTo = \request()->get('price_to');

        $query = Product::query()
            ->where('active', ProductActiveEnum::ACTIVE->value);

        if (request()->filled('type')) {
            $type = request()->type;

            if (
                $type == CategoryTypeEnum::SEARCH_JOB->value ||
                $type == CategoryTypeEnum::JOB->value
            ) {
                $query->whereIn('type', [
                    CategoryTypeEnum::SEARCH_JOB->value,
                    CategoryTypeEnum::JOB->value,
                ]);
            } else {
                $query->where('type', $type);
            }
        }

        if (request()->filled('q')) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . request()->q . '%')
                    ->orWhere('info', 'like', '%' . request()->q . '%');
            });
        }

        if (request()->filled('city_id')) {
            $query->where('city_id', request()->city_id);
        }

        if (request()->filled('area_id')) {
            $query->whereHas('user', fn ($q) =>
            $q->where('area_id', request()->area_id)
            );
        }

        if (request()->filled('category_id')) {
            $query->where(function ($q) {
                $q->where('category_id', request()->category_id)
                    ->orWhere('sub1_id', request()->category_id)
                    ->orWhere('sub2_id', request()->category_id)
                    ->orWhere('sub3_id', request()->category_id)
                    ->orWhere('sub4_id', request()->category_id);
            });
        }

        $query->when(
            request()->filled('price_from'),
            fn ($q) => $q->where('price', '>=', request()->price_from)
        );

        $query->when(
            request()->filled('price_to'),
            fn ($q) => $q->where('price', '<=', request()->price_to)
        );

        $products = $query->latest()->paginate();
        $productsForPagination = clone $products;
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
        return view('theme2.search', compact('products',  'categories','productsForPagination'));
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
