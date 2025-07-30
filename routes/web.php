<?php

use App\Events\MessageSentEvent;
use App\Http\Controllers\ImportController;
use App\Models\Interaction;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\ShippingPrice;
use App\Models\User;
use App\Service\SmsService;
use Illuminate\Support\Facades\Route;
use Mockery\Exception;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*Auth::routes([
    'verify' => true,
    'login' => false,
]);*/

Route::get('oauth/redirect/google', function () {

    return Socialite::driver('google')->redirect();

})->name('google.auth.site');



Route::get('oauth/callback/google', function () {

    $user = Socialite::driver('google')->user();

return $user;
    // $user->token
});
Route::middleware('throttle:60,1')->group(function () {
    Route::get('download-app',function(){
        return response()->file(Setting::first()?->getFirstMediaPath('apk'),[
            'Content-Type' => 'application/vnd.android.package-archive',
            'Content-Disposition' => 'attachment; filename="myapp.apk"',
        ]);
    });
    Route::get('login', [\App\Http\Controllers\Web\AuthController::class, 'loginUi'])->name('login.ui');
    Route::get('forget-password', [\App\Http\Controllers\Web\AuthController::class, 'forgetPasswordUi'])->name('forget-password.ui');
    Route::get('change-password', [\App\Http\Controllers\Web\AuthController::class, 'changePasswordUi'])->name('change-password.ui');
    Route::get('register', [\App\Http\Controllers\Web\AuthController::class, 'registerUi'])->name('register.ui');
    Route::post('login', [\App\Http\Controllers\Web\AuthController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\Web\AuthController::class, 'logout'])->name('logout')->middleware('auth:web');
    Route::post('register', [\App\Http\Controllers\Web\AuthController::class, 'register'])->name('register');
    Route::post('forget-password', [\App\Http\Controllers\Web\AuthController::class, 'forgetPassword'])->name('forget-password');
    Route::post('change-password', [\App\Http\Controllers\Web\AuthController::class, 'changePassword'])->name('change-password');
    Route::resource('/', \App\Http\Controllers\Web\IndexController::class)->only('index');
    Route::resource('/search', \App\Http\Controllers\Web\SearchController::class)->only('index');
    Route::resource('/jobs', \App\Http\Controllers\Web\JobController::class)->only('index', 'show');
    Route::resource('/tenders', \App\Http\Controllers\Web\TenderController::class)->only('index', 'show');
    Route::resource('/services', \App\Http\Controllers\Web\ServiceController::class)->only('index', 'show');
    Route::resource('/posts', \App\Http\Controllers\Web\PostController::class)->only('index', 'show');
    Route::resource('/pricing', \App\Http\Controllers\Web\PricingController::class)->only('index', 'store');
    Route::get('/profile/{id?}', [\App\Http\Controllers\Web\SellerController::class, 'profile'])->name('seller.profile');
    Route::get('/category/{id}', [\App\Http\Controllers\Web\CategoryController::class, 'show'])->name('category.show');


    Route::middleware('auth:web')->group(function () {
        Route::resource('/my-profile', \App\Http\Controllers\Web\ProfileController::class)
            ->only('index', 'store')
            ->names([
                'index' => 'profile.index',
                'store' => 'profile.store',
            ]);
        Route::resource('/comments', \App\Http\Controllers\Web\CommentController::class)->only(['store']);
        Route::resource('/communities', \App\Http\Controllers\Web\CommunityController::class)->only(['index', 'show', 'store']);
        Route::resource('/messages', \App\Http\Controllers\Web\MessageController::class)->only(['store']);
        Route::resource('/balances', \App\Http\Controllers\Web\BalanceController::class)->only(['index']);
        Route::resource('/invoices', \App\Http\Controllers\Web\InvoiceController::class)->only(['index', 'update']);
        Route::resource('/my-invoices', \App\Http\Controllers\Web\MyInvoiceController::class)->only(['index']);
        Route::resource('/orders', \App\Http\Controllers\Web\OrderController::class)->only(['index','store']);
        Route::post('/markets/followers', [\App\Http\Controllers\Web\SellerController::class, 'followers']);
        Route::post('/products/like', [\App\Http\Controllers\Web\PostController::class, 'like'])->name('post.like');
        Route::resource('/carts', \App\Http\Controllers\Web\CartController::class)->only(['index', 'show', 'store', 'destroy', 'update'])/*->middleware(\App\Http\Middleware\RateLimitPerSecond::class)*/;
        Route::resource('/charges', \App\Http\Controllers\Web\ChargeController::class)->only(['index']);
        Route::resource('/charges', \App\Http\Controllers\Web\ChargeController::class)->only(['index']);
        Route::resource('/galleries', \App\Http\Controllers\Web\GalleryController::class)->only(['show']);
        Route::resource('/notifications', \App\Http\Controllers\Web\NotificationController::class)->only(['index']);

    });

    Route::get('/.well-known/assetlinks.json', function () {
        return json_decode('[{
  "relation": ["delegate_permission/common.handle_all_urls"],
  "target": {
    "namespace": "android_app",
    "package_name": "com.mada.company.ali.basha",
    "sha256_cert_fingerprints":
    ["38:87:1C:5A:17:98:C0:AE:30:7D:58:50:38:80:6B:6E:18:CD:23:A0:72:74:B8:46:DB:5F:B2:AD:A6:49:F9:82",
    "77:6B:B2:2A:A3:E2:2B:1D:2A:6C:90:C8:63:59:E6:C6:A2:A5:63:C1:30:8C:9D:0A:E4:C8:6E:E3:79:DC:B8:56"]
  }
}]');
    });
});


####################################################################
###################### IMPORT DATA #################################
//Route::post('/import', [ImportController::class, 'import'])->name('import');
/*Route::get('/import', function () {
  return  \App\Helpers\StrHelper::generateMd5();
    return view('import');
});*/

Route::get('/download-file/{record}', function (\App\Models\Export $record) {
    $path = "filament_exports/$record->id/$record->file_name.xlsx";

    if (!Storage::disk('local')->exists($path)) {
       return "{$path}";
    }

    return Storage::disk('local')->download($path);
})->name('download.file');

Route::get('testnot/{id?}', function ($id = null) {

  /*  DB::update("
    UPDATE users
    JOIN (
        SELECT user_id, MIN(category_id) as category_id
        FROM products
        GROUP BY user_id
    ) as p ON users.id = p.user_id
    SET users.category_id = p.category_id
    WHERE users.category_id IS NULL
");*/


    return 'success';
});
Route::get('/server-resources', function () {
    return [
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'disk_free_space' => disk_free_space('/'),
        'disk_total_space' => disk_total_space('/'),
        'cpu_load' => sys_getloadavg()
    ];
});
