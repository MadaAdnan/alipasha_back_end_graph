<?php

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
Route::get('/', function () {
    $search='ايام';
    try{
        $user=User::whereRaw('MATCH(name) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search])->get();
        return dd($user);

    }catch (Exception |Error $e){
        dd($e->getMessage());
    }




    return view('welcome');
});
