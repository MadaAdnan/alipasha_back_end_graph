<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Enums\ProductActiveEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Interaction;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Http\Request;

class PostController extends Controller
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

        $post=Product::whereActive(ProductActiveEnum::ACTIVE->value)->with('comments')->find($id);
        $categories = Category::where('is_active', true)
            ->where(fn($query) => $query->where('type', CategoryTypeEnum::PRODUCT->value)->orWhere('type', CategoryTypeEnum::RESTAURANT->value))->orderBy('sortable')->get();

        return view('web.post-info',compact('post','categories'));
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

    public function like(Request $request){
        $this->validate($request,[
            'productId'=>'required'
        ]);
        $productId=$request->productId;
        $product=Product::find($productId);
        if(Like::where(['product_id'=>$productId,'user_id' => auth()->id()])->exists()){
            Like::where(['product_id'=>$productId,'user_id' => auth()->id()])->delete();
        }else{
            Like::create([
                'user_id'=>auth()->id(),
                'product_id'=>$productId,
            ]);
            try{
                Interaction::updateOrCreate([
                    'user_id'=>auth()->id(),
                    'category_id'=>$product->category_id,

                ],[
                    'visited'=> \DB::raw('visited + 1'),
                ]);
            }catch(\Exception | \Error $e){
                info('ERROR:'.$e->getMessage());
            }
        }
        return back();

    }
}
