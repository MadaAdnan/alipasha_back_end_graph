<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts=Cart::where('user_id',auth()->id())->groupBy('seller_id')->get();
        return view('web.carts',compact('carts'));
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
        $product = Product::find($request->productId);
        if ($product && ($product->type == CategoryTypeEnum::RESTAURANT->value || $product->type == CategoryTypeEnum::PRODUCT->value)) {
            $cart = Cart::where(['user_id' => auth()->id(), 'product_id' => $product->id])->first();
            if ($cart == null) {
                $cart = Cart::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'seller_id' => $product->user_id,
                    'qty' => 1,
                ]);
            } else {
                $cart->update(['qty' => $cart->qty + 1]);
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $user=User::findOrFail($id);
       $items=Cart::where(['user_id'=>auth()->id(),'seller_id' => $id])->get();
       return view('web.cart',compact('user','items'));
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
