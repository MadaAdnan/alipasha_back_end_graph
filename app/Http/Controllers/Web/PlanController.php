<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans=Plan::where('is_active',1)->get();
        return view('theme2.plans',compact('plans'));
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
        $planId=$request->planId;
        $plan=Plan::where('is_active',1)->find($planId);
        if(!$plan){
            return back()->with('error','الخطة غير متوفرة');
        }
        $price=$plan->is_discount?$plan->discount:$plan->price;
        if(auth()->user()->getTotalBalance()<$price){
            return back()->with('error','رصيدك غير كافي');
        }
        \DB::beginTransaction();
        try{
            Balance::create([
                'user_id'=>auth()->user()->id,
                'info'=>"اشتراك بالخطة {$plan->name}",
                'debit'=>$price,
                'credit'=>0,
            ]);
            $plan->users()->attach(auth()->user()->id);
            return back()->with('success','تم الاشتراك بنجاح');
        }catch(\Exception $e){
            \DB::rollBack();
            return back()->with('error',$e->getMessage());
        }

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
