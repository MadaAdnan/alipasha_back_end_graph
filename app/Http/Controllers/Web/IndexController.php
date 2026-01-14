<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\LevelUserEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Community;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->where(['is_active' => true, 'is_main' => true])
            ->whereIn('type', [
                CategoryTypeEnum::PRODUCT->value,
//                CategoryTypeEnum::JOB->value,
//                CategoryTypeEnum::SEARCH_JOB->value,
//                CategoryTypeEnum::TENDER->value,
//                CategoryTypeEnum::NEWS->value,
            ])
            ->orderBy('sortable')
            ->orderByRaw("FIELD(type, 'product', 'job', 'search_job','tender','service','news')")
            ->get();
        $categoriesWithProducts = Category::where('is_active', true)
            ->where('is_main', true)
            ->where('type', CategoryTypeEnum::PRODUCT->value)


            ->withCount([
                'products as products_count' => function ($query) {
                    $query->active()
                        ->upTo20()
                        ->whereHas('media')
                        ->orderByRaw("CASE WHEN level = 'special' THEN 1 ELSE 2 END")
                        ->orderBy('created_at', 'DESC');
                }
            ])

            ->having('products_count', '>=', 8)

            ->inRandomOrder()
            ->take(5)

            ->with([
                'products' => function ($query) {
                    $query->active()
                        ->upTo20()
                        ->whereHas('media')
                        ->orderByRaw("CASE WHEN level = 'special' THEN 1 ELSE 2 END")
                        ->orderBy('created_at', 'DESC');
                }
            ])

            ->get();

        $categoriesWithProducts->each(function ($cat) {
            $cat->setRelation('products', $cat->products->take(8));
        });

        return view('theme2.index', compact('categories', 'categoriesWithProducts'));
    }

    private function getPopularCategoryProducts()
    {
        return Interaction::where('user_id', auth()->id())->whereNotNull('category_id')
            ->latest()
            ->groupBy('category_id')
            ->orderByRaw('SUM(visited) DESC')
            ->pluck('category_id')->toArray();
    }

    private function getPopularSelelrProducts()
    {
        return Interaction::where('user_id', auth()->id())->whereNotNull('seller_id')
            ->groupBy('seller_id')
            ->pluck('seller_id')->toArray();
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
