<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class MyInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices=Invoice::where('user_id',auth()->id())->latest()->paginate(30);
        return view('web.my-invoice',compact('invoices'));
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
        $product=Product::find($request->product_id);
     $invoice=   Invoice::create([
            'user_id'=>auth()->id(),
           'phone' => auth()->user()->phone,
            'address' => auth()->user()->address,
            'seller_id' => $product->user_id,
            'status' => OrderStatusEnum::PENDING->value,
            'total' => $product->price,
        ]);
        $invoice->items()->create([
            'qty' => 1,
            'product_id' => $product->id,
            'price' => $product->price,
            'total' => $product->price,
        ]);
        return back();
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
