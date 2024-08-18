<?php

use App\Models\ShippingPrice;
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
Route::get('/', function () {

   for($i=0;$i<40;$i++){
       \App\Models\Message::create([
           'community_id'=>2,
           'message'=>fake()->name,
           'user_id'=>$i%2==0?1:3,
           'created_at'=>fake()->dateTimeBetween('-3 days','now'),
       ]);
   }

    /*    $products = DB::select(DB::raw("
        SELECT *
        FROM (
            SELECT
                id, name, category,type
                ROW_NUMBER() OVER (PARTITION BY category ORDER BY id) AS row_num,
                ROW_NUMBER() OVER (ORDER BY category, id) AS overall_num
            FROM products
        ) AS numbered_products
        WHERE category = 'product' OR (category = 'news' AND (row_num - 1) % 10 = 0)
        ORDER BY overall_num
    "));*/
    /* $products = DB::select("
     SELECT *
     FROM (
         SELECT
             id, name, category_id, type,
             ROW_NUMBER() OVER (PARTITION BY category_id ORDER BY id) AS row_num,
             ROW_NUMBER() OVER (ORDER BY category_id, id) AS overall_num
         FROM products
     ) AS numbered_products
     WHERE type = 'product' OR (type = 'news' AND (row_num - 1) % 10 = 0)
     ORDER BY overall_num
 ");*/

    return view('welcome');
});
