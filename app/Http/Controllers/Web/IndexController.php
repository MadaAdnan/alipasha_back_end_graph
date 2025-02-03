<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelUserEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $specialSeller=User::where([
            'level'=>LevelUserEnum::SELLER->value,
            'is_special' => true,
            'is_active' => true,
            ])->get();
       $products= Product::where(function($query){
            $query->where('active',ProductActiveEnum::ACTIVE->value);
            $query->where(fn($query)=>
            $query->where('type',CategoryTypeEnum::PRODUCT->value)
                ->orWhere('type',CategoryTypeEnum::JOB->value)
                ->orWhere('type',CategoryTypeEnum::SEARCH_JOB->value)
                ->orWhere('type',CategoryTypeEnum::NEWS->value)
                ->orWhere('type',CategoryTypeEnum::TENDER->value)
            );
        })->latest()->paginate(35);
$categories=Category::where('is_active',true)
    ->where(fn($query)=>$query->where('type',CategoryTypeEnum::PRODUCT->value)->orWhere('type',CategoryTypeEnum::RESTAURANT->value))->orderBy('sortable')->get();
        return view('web.index', compact('specialSeller','products','categories'));
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
