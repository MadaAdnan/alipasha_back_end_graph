<?php

use App\Jobs\SendFirebaseNotificationJob;
use App\Models\ClickWhats;
use App\Models\Like;
use App\Models\Product;
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
   return \App\Models\Product::active()->product()->where(fn($query)=>$query->where('name', 'like', "%{$q}%")
 /*  ->orWhere('expert', 'like', "%{$q}%")*/
   )->pluck('name')->toArray();

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
});
Route::middleware(\App\Http\Middleware\PassApiStatisticsMiddleware::class)->group(function () {
    Route::get('users-count', [\App\Http\Controllers\Api\StatisticsController::class, 'userCount']);
    Route::get('users-plans', [\App\Http\Controllers\Api\StatisticsController::class, 'userPlans']);
    Route::get('orders-count', [\App\Http\Controllers\Api\StatisticsController::class, 'ordersCount']);
    Route::get('users-affiliate', [\App\Http\Controllers\Api\StatisticsController::class, 'usersAffiliate']);
});
