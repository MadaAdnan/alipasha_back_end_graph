<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices=Invoice::where('seller_id',auth()->id())->latest()->paginate(20);
        return view('web.invoice',compact('invoices'));
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
        $invoice=Invoice::findOrFail($id);
        $status=$request->status;
        $msg='';
        if($status==OrderStatusEnum::AGREE->value){
            $invoice->update([
                'status'=>OrderStatusEnum::AGREE->value,
            ]);
            $msg="تمت الموافقة على الطلب";
        }
        if($status==OrderStatusEnum::CANCELED->value){
            $invoice->update([
                'status'=>OrderStatusEnum::CANCELED->value,
            ]);
            $msg="تم رفض الطلب";
        }
        return back()->with('success',$msg);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
