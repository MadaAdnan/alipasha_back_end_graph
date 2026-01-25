<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Advice;
use App\Models\City;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::where('is_active', true)->orderBy('city_id')->get();
        $type = \request()->get('type') ?? 'products';
        $ads = [];
        if ($type == 'ads') {
            $ads = Advice::where('user_id', auth()->id())->where('expired_date', '>', now())->orderBy('expired_date')->withCount('views')->get();

        }
        $products = Product::whereNot('type', 'service')->where('user_id', auth()->id())->latest()->paginate(20);
        return view('theme2.profile', compact('cities', 'type', 'ads', 'products'));
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
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'phone_code' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',
        ], [
            'name.required' => 'الرجاء ادخال الاسم',
            'phone.required' => 'الرجاء ادخال رقم الهاتف',
            'phone_code.required' => 'الرجاء ادخال رقم الهاتف',
            'address.required' => 'الرجاء ادخال العنوان',
            'city_id.required' => 'الرجاء ادخال المدينة',
            'area_id.required' => 'الرجاء ادخال المنطقة',
        ]);
        /**
         * @var $user User
         */
        $user = auth()->user();
        $data = [
            'name' => $user->is_social ? $user->name : $request->name,

            'phone' => $request->phone,
            'phone_code' => $request->phone_code,
            'address' => $request->address,
            'city_id' => $request->city_id,
            'area_id' => $request->area_id,
        ];
        if (auth()->user()->is_verified) {
            $data['seller_name'] = $request->seller_name;
        }
        $user->update($data);
        return back()->with('success', 'نجاح العملية');
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
