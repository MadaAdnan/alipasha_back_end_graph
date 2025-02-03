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
        $type=\request()->get('type');
        $text=\request()->get('q');
        $city=\request()->get('city');
        $category=\request()->get('category');
        $department=\request()->get('department');
        $price=\request()->get('price');
        $products=Product::where('active',ProductActiveEnum::ACTIVE->value)
            ->when(!empty($type),function($query)use($type){
                if($type==CategoryTypeEnum::SEARCH_JOB->value || $type==CategoryTypeEnum::JOB->value ){
                    $query->whereIn('type',[
                        CategoryTypeEnum::SEARCH_JOB->value,
                        CategoryTypeEnum::JOB->value,
                    ]);
                }else{
                   $query->where('type',$type);
                }
            })
            ->when(!empty($text),fn($query)=>$query->where('name','like',"%{$text}%")->orWhere('info','like',"%{$text}%"))
            ->when(!empty($city),fn($query)=>$query->where('city_id',$city))
            ->when(!empty($category),fn($query)=>$query->where('category_id',$category))
            ->when(!empty($department),fn($query)=>$query->where('sub1_id',$department))
            ->when(!empty($price),fn($query)=>$query->whereBetween('price',[0,$price]))
            ->latest()
        ->paginate();
        $cities=City::where('is_active',true)->orderBy('city_id')->get();
        $categories=Category::where('is_active')->get();
        return view('web.search',compact('products','cities','categories'));
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
