<?php

use App\Events\MessageSentEvent;
use App\Http\Controllers\ImportController;
use App\Models\Interaction;
use App\Models\ShippingPrice;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Mockery\Exception;

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
Route::resource('/',\App\Http\Controllers\Web\IndexController::class)->only('index');
Route::resource('/search',\App\Http\Controllers\Web\SearchController::class)->only('index');
Route::resource('/jobs',\App\Http\Controllers\Web\JobController::class)->only('index','show');
Route::resource('/tenders',\App\Http\Controllers\Web\TenderController::class)->only('index','show');
Route::resource('/services',\App\Http\Controllers\Web\ServiceController::class)->only('index','show');
Route::resource('/posts',\App\Http\Controllers\Web\PostController::class)->only('index','show');


Route::middleware('auth:web')->group(function (){
    Route::resource('/profile',\App\Http\Controllers\Web\ProfileController::class);
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

####################################################################
###################### IMPORT DATA #################################
//Route::post('/import', [ImportController::class, 'import'])->name('import');
/*Route::get('/import', function () {
  return  \App\Helpers\StrHelper::generateMd5();
    return view('import');
});*/


Route::get('testnot/{id?}',function($id=null){
   $communities=\App\Models\Community::withCount('users')->having('users_count','<=',1)->get();
   dd($communities[rand(0,$communities->count()-1)]->users);
/*$users= User::orWhere(['is_seller'=>0,'level'=>\App\Enums\LevelUserEnum::USER->value])->whereHas('products')->update([
    'is_seller'=>1,
    'level'=>\App\Enums\LevelUserEnum::SELLER->value,
//]);*/
//    \App\Models\Plan::where('type',\App\Enums\PlansTypeEnum::SERVICE->value)->whereNull('ads_count')->update(['ads_count'=>0]);
//    \App\Models\Plan::where('type',\App\Enums\PlansTypeEnum::SERVICE->value)->whereNull('products_count')->update(['products_count'=>0]);
//    \App\Models\Plan::where('type',\App\Enums\PlansTypeEnum::SERVICE->value)->whereNull('special_count')->update(['special_count'=>0]);
return 'success';
    $message=\App\Models\Message::create([
      'body'=>fake()->paragraph,
      'type'=>'text',
      'community_id'=>$id??32,
      'user_id'=>6680,
  ]);
    try {
        event(new MessageSentEvent($message));
    } catch (Exception $e) {
        info('Error Websockets');
    }

});

