<?php

namespace App\Http\Controllers\Web;

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
        $cities=City::whereIsActive(true)->whereIsMain(true)->get();
        $cityId=\request()->get('city_id');
        $search=\request()->get('q');
        $category=Category::findOrFail($id);
        $categories=$category->children;
        $category_id=\request()->get('category_id');
        $products=Product::where(['category_id'=>$id,'active' => ProductActiveEnum::ACTIVE->value])
            ->when(!empty($search),fn($query)=>$query->where('name','Like',"%{$search}%")->orWhere('expert','Like',"%{$search}%"))
            ->when(!empty($cityId),fn($query)=>$query->whereHas('city',fn($q)=>$q->where('city_id',$cityId)))
            ->when($category_id!=null,fn($query)=>$query->where('sub1_id',$category_id))->paginate(28);
        return view('web.section_show',compact('category','products','categories','cities'));
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
