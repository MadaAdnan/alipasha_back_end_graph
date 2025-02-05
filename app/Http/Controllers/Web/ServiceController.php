<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
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
        $city=\request()->get('city');
        $town=\request()->get('town');

        $q=\request()->get('q');
        $cities=City::where('is_active',true)->get();
        $services_count=Product::service() ->where('active',ProductActiveEnum::ACTIVE->value)->count();
        $views=ProductView::whereHas('product',fn($query)=>$query->where('type',CategoryTypeEnum::SERVICE->value))->sum('count');
        $sellers=User::whereHas('products',fn($query)=>$query->where('type',CategoryTypeEnum::SERVICE->value))->count();
        $services=Product::service() ->where('active',ProductActiveEnum::ACTIVE->value)
            ->when(!empty($q),fn($query)=>$query->where('info','like',"%{$q}%"))

            ->when(!empty($city),fn($query)=>$query->whereHas('city',fn($query)=>$query->where('cities.city_id',$city)))
            ->when(!empty($town),fn($query)=>$query->where('city_id',$town))

            ->latest()->paginate(35);

        return view('web.services',compact('cities','services','services_count','views','sellers'));
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
