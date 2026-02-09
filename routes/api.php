<?php

use App\Enums\OrderStatusEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\City;
use App\Models\ClickWhats;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Like;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Broadcast::routes(['middleware' => ['auth:sanctum', 'throttle:60,1']]);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    Log::info($request->header());
    return $request->user();
});
Route::get('like/{userId}/{productId}', function ($userId, $productId) {
    $product = \App\Models\Product::find($productId);
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $like = Like::where(['product_id' => $productId, 'user_id' => $userId])->exists();
    if ($like) {
        Like::where(['product_id' => $productId, 'user_id' => $userId])->delete();
    } else {
        Like::create(['product_id' => $productId, 'user_id' => $userId]);
        \App\Models\Interaction::updateOrCreate([
            'user_id' => $userId,
            'seller_id' => $product->user_id,
            'category_id' => $product->category_id,

        ], [
            'visited' => 1,
        ]);
    }

    // إعادة تحميل العد بعد التحديث
    return $product->likes()->count();
})->name('api.like');
Route::get('suggestions', function () {
   $q=\request()->get('q');
   $category=\request()->get('category_id');
    $products = \App\Models\Product::active()->product()

        ->when($category, function($query) use ($category) {
            $query->where(function($q) use ($category) {
                $q->where('category_id', $category)
                    ->orWhere('sub1_id', $category)
                    ->orWhere('sub2_id', $category)
                    ->orWhere('sub3_id', $category)
                    ->orWhere('sub4_id', $category);
            });
        })

        ->when($q, function($query) use ($q) {
            $query->where('name', 'like', "%{$q}%");
        })
        ->select('name')
        ->distinct()
        ->limit(4)
        ->pluck('name')
        ->toArray();

    return $products;

});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/messages', \App\Http\Controllers\Api\V1\MessageController::class)->only('store');
    Route::post('click-whats', function (Request $request) {

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
    });
    Route::post('orders', function (Request $request) {
      
        if (!auth()->user()->is_active) {
            return response()->json(['status'=>'error','msg' => 'حسابك غير مفعل'], 403);
        }


        \DB::beginTransaction();
        try {

            $weight = 0;
            if (!auth()->check() || auth()->id() == null) {
                return response()->json(['status'=>'error','msg' => 'خطأ في الطلب يرجى المحاولة من جديد'], 403);

            }
            $seller = User::findOrFail($request->seller_id);


            $invoice = new Invoice();
            $invoice->seller_id = $seller->id;
            $invoice->user_id = auth()->id();
            $invoice->phone =  auth()->user()->phone;
            $invoice->address =  auth()->user()->address;
            $invoice->status = OrderStatusEnum::PENDING->value;
            $invoice->save();


            $total = 0;
            foreach ($request->data as $item) {
                $product = Product::find($item['product_id']);
                if ($product->is_delivery) {
                    $weight += $product->weight;
                }
                if (!$product) {
                   return response()->json(['status'=>'error','msg'=>"المنتج {$item['product_id']} غير موجود."]);
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
            $invoice->total =0; // $total;
            $invoice->save();

            \DB::commit();
            return response()->json(['status'=>'success']);
        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            return response()->json(['status'=>'error','msg' => $e->getMessage()], 403);
        }

    });
});
Route::middleware(\App\Http\Middleware\PassApiStatisticsMiddleware::class)->group(function () {
    Route::get('users-count', [\App\Http\Controllers\Api\StatisticsController::class, 'userCount']);
    Route::get('users-plans', [\App\Http\Controllers\Api\StatisticsController::class, 'userPlans']);
    Route::get('orders-count', [\App\Http\Controllers\Api\StatisticsController::class, 'ordersCount']);
    Route::get('users-affiliate', [\App\Http\Controllers\Api\StatisticsController::class, 'usersAffiliate']);
});
