<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\City;
use App\Models\Order;
use App\Models\ShippingPrice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::whereIsMain(true)->whereIsDelivery(true)->with('children')->get();
        $pricing = ShippingPrice::get();
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(30);
        return view('web.orders', compact('orders', 'cities', 'pricing'));
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
        $this->validate($request, [
            'areaSource' => 'required|exists:cities,id',
            'nameSource' => 'required',
            'nameTarget' => 'required',
            'addressSource' => 'required',
            'addressTarget' => 'required',
            'phoneTarget' => 'required',
            'weight' => 'required',
            'width' => 'required',
            'length' => 'required',
            'height' => 'required',
            'areaTarget' => 'required|exists:cities,id',
        ], [
            'areaSource.required' => 'الحقل مدينة المرسل مطلوب',
            'nameSource.required' => 'الحقل اسم المرسل مطلوب',
            'nameTarget.required' => 'الحقل اسم المرسل إليه مطلوب',
            'addressSource.required' => 'الحقل عنوان المرسل مطلوب',
            'addressTarget.required' => 'الحقل عنوان المرسل إليه مطلوب',
            'phoneTarget.required' => 'الحقل هاف المرسل إليه مطلوب',
            'weight.required' => 'الحقل الوزن مطلوب',
            'width.required' => 'الحقل العرض مطلوب',
            'length.required' => 'الحقل الطول مطلوب',
            'height.required' => 'الحقل الإرتفاع مطلوب',
            'areaTarget.required' => 'الحقل مدينة المرسل إليه مطلوب',
        ]);

        $source = City::find($request->areaSource);
        $target = City::find($request->areaTarget);
        if ($source->is_delivery == false || $target->is_delivery == false || $source->level == '' || $target->level == '') {
            return back()->with('error', 'الشحن غير متاح في هذه المدن');
        }
        $steps = $source->level + $target->level - 1;
        $pricingWeight = ShippingPrice::where('weight', '>=', $request->weight)?->internal_price;
        $size = (($request->height * 0.01) * ($request->width * 0.01) * ($request->length * 0.01) / 100000);
        $pricingSize = ShippingPrice::where('size', '>=', $size)?->internal_price;
        if ($pricingWeight == null || $pricingSize == null) {
            return back()->with('error', 'الحمولة أكبر من الحد المسموح به');
        }
        $far = $pricingWeight > $pricingSize ? $pricingWeight : $pricingSize;
        $far = $far + (($far / 3) * $steps);
        if (auth()->user()->getTotalBalance() < $far) {
            return back()->with('error', 'لا تملك رصيد كافي لإتمام العملية');
        }

        \DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'from_id' => $source->id,
                'to_id' => $target->id,
                'weight' => $request->weight,
                'size' => $size,
                'width' => $request->width,
                'length' => $request->length,
                'height' => $request->height,
                'receive_name' => $request->nameTarget,
                'receive_address' => $request->addressTarget,
                'receive_phone' => $request->phoneTarget,

                'sender_name' => $request->nameSource,
                'sender_phone' => auth()->user()->phone,
                'sender_address' => $request->addressSource,
                'price' => $far,
                'note' => $request->note,
            ]);
            Balance::create([
                'user_id' => auth()->id(),
                'debit' => $far,
                'credit' => 0,
                'info' => 'شحن مخصص طلب رقم #' . $order->id
            ]);
            \DB::commit();
            return back()->with('success', 'تم إرسال الطلب بنجاح');
        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
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
