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
        $priceTo = \request()->filled('price_to') ?\request()->get('price_to'):1000000;
dd($priceTo,$priceFrom);
        $products = Product::query()->where('active', ProductActiveEnum::ACTIVE->value);
        if(!empty($type)){
            if ($type == CategoryTypeEnum::SEARCH_JOB->value || $type == CategoryTypeEnum::JOB->value) {
                $products->whereIn('type', [
                    CategoryTypeEnum::SEARCH_JOB->value,
                    CategoryTypeEnum::JOB->value,
                ]);
            } else {
                $products->where('type', $type);
            }
        }
        if(!empty($text)){
            $products->where(fn($q) => $q->where('name', 'like', "%{$text}%")->orWhere('info', 'like', "%{$text}%"));
        }
        if(!empty($city)){
            $products->where('city_id', $city);
        }
        if(!empty($area)){
            $products->whereHas('user', fn($query) => $query->where('area_id', $area));
        }
           if(!empty($category)){
               $products->where(fn($query)=>$query->where('category_id', $category)->orWhere('sub1_id',$category)
                   ->orWhere('sub2_id',$category)
                   ->orWhere('sub3_id',$category)
                   ->orWhere('sub4_id',$category));

           }
            $products  ->whereBetween('price', [$priceFrom,$priceTo]);


          $products=  $products->latest()->paginate();
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
