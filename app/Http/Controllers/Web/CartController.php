<?php

namespace App\Http\Controllers\Web;

use App\Enums\CategoryTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ShippingPrice;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::where('user_id', auth()->id())->groupBy('seller_id')->get();
        return view('web.carts', compact('carts'));
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
                if ($request->type == 'min') {
                    if ($cart->qty > 1) {
                        $cart->update(['qty' => $cart->qty - 1]);
                    }

                } else {
                    $cart->update(['qty' => $cart->qty + 1]);
                }

            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $items = Cart::where(['user_id' => auth()->id(), 'seller_id' => $id])->get();
        $weight = 0;
        $shipping = 0;
        $carts = Cart::where(['user_id' => auth()->id(), 'seller_id' => $id])->get();
        foreach ($carts as $cart) {
            $product = $cart->product;


            $weight += $product->weight * $cart->qty;
        }

        if ($carts->count() > 0) {
            $shippingPrice = ShippingPrice::where('weight', '>=', $weight)->orderBy('weight')->first();
            if ($shippingPrice == null) {
                $shippingPrice = ShippingPrice::orderBy('weight', 'desc')->first();
            }
            $shipping = $shippingPrice->internal_price;
            $steps = ($cart->seller->area?->level ?? 0) + ($cart->user->area?->level ?? 0) - 1;
            $ratio = $shipping / 3;

            $shipping = $shipping + ($ratio * $steps);

        }

        return view('web.cart', compact('user', 'items', 'shipping'));
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
        $carts = Cart::/*whereHas('product', fn($query) => $query->where('is_delivery', true))->*/ where(['user_id' => auth()->id(), 'seller_id' => $id])->get();
        $total = 0;
        $shipping = 0;
        $size = 0;
        $weight = 0;
        $message = "السلام عليكم ورحمة الله وبركاته ";
        $message .= "\n ";
        $message .= "📦 طلب جديد من تطبيق علي باشا:";
        foreach ($carts as $cart) {
            $product = $cart->product;
            $message .= "\n ";
            $message .= "معرف المنتج : " . $product->id;
            $message .= "\n ";
            $message .= "اسم المنتج : " . $product->name;
            $message .= "\n ";
            $message .= "الكمية : " . $cart->qty;
            $message .= "\n ";
            $message .= "السعر : " . $product->getPrice();
            $message .= "\n ";


            $total += $product->getPrice() * $cart->qty;

            $weight += $product->weight * $cart->qty;
        }
        $shippingPrice = ShippingPrice::where('weight', '>=', $weight)->orderBy('weight')->first();
        if ($shippingPrice == null) {
            $shippingPrice = ShippingPrice::orderBy('weight', 'desc')->first();
        }

        $shipping = $shippingPrice->internal_price;
        $ratio = $shipping / 3;
        $steps = ($cart->seller->area?->level ?? 0) + ($cart->user->area?->level ?? 0) - 1;
        $shipping = $shipping + ($steps * $ratio);
        \DB::beginTransaction();
        try {
            $result =/* $shipping +*/
                $total;
            /*if (auth()->user()->getTotalBalance() < $result) {
                throw new \Exception("لا تملك رصيد مافي");
            }*/
            if ($carts->count() > 0)

                $invoice = Invoice::create([
                    'user_id' => auth()->id(),
                    'seller_id' => $id,
                    'weight' => $weight,
                    'size' => $size,
                    'shipping' => 0,//$shipping,
                    'total' => $total,
                    'phone' => auth()->user()->phone,
                    'address' => auth()->user()->address,
                ]);
            /* Balance::create([
                 'user_id'=>auth()->id(),
                 'debit'=>$result,
                 'info'=>'قيمة شحن طلب رقم '.$invoice->id,
             ]);*/
            foreach ($carts as $cart) {
                Item::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $cart->product_id,
                    'qty' => $cart->qty,
                    'price' => $cart->product->getPrice(),
                    'total' => $cart->qty *
                        $cart->product->getPrice(),
                ]);

            }
            /**
             * @var $seller User
             */
            $seller = Cart::where(['user_id' => auth()->id(), 'seller_id' => $id])->first()?->product?->user;
            Cart::where(['user_id' => auth()->id(), 'seller_id' => $id])->delete();
            \DB::commit();
            $phone = "";
            if ($seller) {
                $phone = "$seller->phone_code" . "$seller->phone";

            }
            $message .= "\n" . Setting::first()?->footer_order;
            return redirect()->route('my-invoices.index')->with([
                'message' => $message,
                'phone' => $phone
            ]);
        } catch (\Exception|\Error $error) {
            \DB::rollBack();
            return back()->with('error', $error->getMessage() . '-' . $error->getLine());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Cart::find($id)?->delete();
        return back();
    }
}
