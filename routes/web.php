<?php

use App\Http\Controllers\ImportController;
use App\Models\ShippingPrice;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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
Auth::routes([
    'verify' => true,
]);
Route::get('/test', function () {

   /* for ($i = 0; $i < 60; $i++) {
        \App\Models\Message::create([
            'body' => fake()->name,
            'community_id' => 5,
            'type' => 'text',
            'user_id' => User::inRandomOrder()->first()->id,

        ]);
    }*/
//   /* $search='ايام';
//    try{
//        $user=User::whereRaw('MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search])->get();
//        return dd($user);
//
//    }catch (Exception |Error $e){
//        dd($e->getMessage());
//    }*/


    return view('welcome');
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
Route::post('/import', [ImportController::class, 'import'])->name('import');
Route::get('/import', function () {
    return view('import');
});
