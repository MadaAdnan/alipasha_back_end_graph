<?php

use App\Models\Like;
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
Route::get('like/{userId},{productId}', function ($userId, $productId) {
    $like = Like::where(['product_id' => $productId, 'user_id' => $userId])->exists();
    if ($like) {
        Like::where(['product_id' => $productId, 'user_id' => $userId])->delete();
    } else {
        Like::create(['product_id' => $productId, 'user_id' => $userId]);
        $product = \App\Models\Product::find($productId);
        \App\Models\Interaction::updateOrCreate([
            'user_id' => $userId,
            'seller_id' => $product->user_id,
            'category_id' => $product->category_id,

        ], [
            'visited' =>1,
        ]);
    }
    return $product->likes_count??0;
})->name('api.like');
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/messages', \App\Http\Controllers\Api\V1\MessageController::class)->only('store');
});
