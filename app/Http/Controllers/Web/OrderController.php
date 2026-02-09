<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Balance;
use App\Models\City;
use App\Models\ClickWhats;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingPrice;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Log;

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
        $pricingWeight = ShippingPrice::where('weight', '>=', $request->weight)->first()?->internal_price;
        $size = (($request->height * 0.01) * ($request->width * 0.01) * ($request->length * 0.01) / 100000);
        $pricingSize = ShippingPrice::where('size', '>=', $size)->first()?->internal_price;
        if ($pricingWeight == null || $pricingSize == null) {
            return back()->with('error', 'الحمولة أكبر من الحد المسموح به');
        }
        $far = $pricingWeight > $pricingSize ? $pricingWeight : $pricingSize;
        $far = $far + (($far / 3) * $steps);

        if ((auth()->user()->getTotalBalance()) < $far) {
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
                'info' => 'خصم أجور شحن مخصص طلب رقم #' . $order->id
            ]);
            \DB::commit();
            return back()->with('success', 'تم إرسال الطلب بنجاح');
        } catch (\Exception|\Error $e) {
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

    public function addToCart(Request $request)
    {
        if (!auth()->user()->is_active) {
            return response()->json(['status' => 'error', 'msg' => 'حسابك غير مفعل'], 403);
        }


        \DB::beginTransaction();
        try {

            $weight = 0;
            if (!auth()->check() || auth()->id() == null) {
                return response()->json(['status' => 'error', 'msg' => 'خطأ في الطلب يرجى المحاولة من جديد'], 403);

            }
            $seller = User::findOrFail($request->seller_id);


            $invoice = new Invoice();
            $invoice->seller_id = $seller->id;
            $invoice->user_id = auth()->id();
            $invoice->phone = auth()->user()->phone;
            $invoice->address = auth()->user()->address;
            $invoice->status = OrderStatusEnum::PENDING->value;
            $invoice->save();


            $total = 0;
            foreach ($request->data as $item) {
                $product = Product::find($item['product_id']);
                if ($product->is_delivery) {
                    $weight += $product->weight;
                }
                if (!$product) {
                    return response()->json(['status' => 'error', 'msg' => "المنتج {$item['product_id']} غير موجود."]);
                }
                $price = $product->is_discount ? $product->discount : $product->price;
                $total_price = $price * $item['qty'];
                Item::create([
                    'invoice_id' => $invoice->id, // $invoice->id متاح الآن
                    'product_id' => $item['product_id'],
                    'price' => $price,
                    'qty' => $item['qty'],
                    'total' => $total_price,
                ]);
                $total += $total_price;
            }

            $invoice->weight = $weight;
            $invoice->shipping = 0;//$far;
            $invoice->total = 0; // $total;
            $invoice->save();

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception|\Error $e) {
            \DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => $e->getMessage()], 403);
        }

    }

    public function clickWhats(Request $request)
    {

        $productId = $request->product_id;
        $product = Product::find($productId);
        if (!$product) {
            return '';
        }
        $user = auth()->user();
        $name = $product->name ?? \Str::substr($product->expert, 0, 20);
        $data['title'] = 'مراسلة جديدة';
        $data['body'] = "قد يتواصل الزبون {$user->name} عبر واتسأب للإستفسار عن المنتج {$name}";
        try {

            $job = new SendFirebaseNotificationJob([$product->user->device_token], $data);
            dispatch($job);
            ClickWhats::create([
                'product_id' => $productId,
                'user_id' => $user->id,
                'seller_id' => $product->user_id
            ]);
        } catch (Exception|\Error $e) {
            Log::error($e->getMessage());
        }
        return $product->user?->full_phone;
    }

}
