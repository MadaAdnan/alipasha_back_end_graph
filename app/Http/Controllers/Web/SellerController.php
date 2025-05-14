<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProductActiveEnum;
use App\GraphQL\Mutations\FollowAccount;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\User;
use App\Models\UserFollow;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @param string $id
     */
    public function profile(string $id=null)
    {
        if($id!=null){
            $store=User::findOrFail($id);
        }else{
            $id=\request()->input('id');
            $store=User::findOrFail($id);
        }

        $categoryId=\request()->get('category_id');
        $products=Product::whereActive(ProductActiveEnum::ACTIVE->value)->where('user_id',$id)
            ->when(!empty($categoryId),fn($query)=>$query->where('category_id',$categoryId))
            ->inRandomOrder()->latest()->paginate(21);
        $categoryIds=$store->products->pluck('category_id')->toArray();
        $categories = Category::whereIn('id', $categoryIds)
            ->withCount(['products' => function ($query) use ($store) {
                $query->where('user_id', $store->id);
            }])->get();
        return view('web.store',compact('store','products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function followers(Request $request)
    {
        /**
         * @var $user User
         *
         */
        $sellerId = $request->storeId;
        $user = auth()->user();
        $follow = UserFollow::where(['seller_id' => $sellerId, 'user_id' => $user->id])->exists();
        if ($follow) {
            UserFollow::where(['seller_id' => $sellerId, 'user_id' => $user->id])->delete();


            if (auth()->check()) {
                Interaction::where([
                    'user_id' => auth()->id(),
                    'seller_id' => $sellerId,

                ])->delete();
            }
        } else {
            UserFollow::create(['seller_id' => $sellerId, 'user_id' => $user->id]);


            if (auth()->check()) {
                Interaction::updateOrCreate([
                    'user_id' => auth()->id(),
                    'seller_id' => $sellerId,

                ], [
                    'visited' => \DB::raw('visited + 1'),
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
