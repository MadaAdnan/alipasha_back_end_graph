<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $category=Category::findOrFail($id);
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
        $products=Product::active()->upTo20()->where(function($query)use($id){
            $query->where('category_id',$id);
            $query->orWhere('sub1_id',$id);
            $query->orWhere('sub2_id',$id);
            $query->orWhere('sub3_id',$id);
            $query->orWhere('sub4_id',$id);
        })  ->latest()
            ->paginate();
        $cloneProducts=clone $products;
        return view('theme2.category',compact('category','products','categories','cloneProducts'));
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
