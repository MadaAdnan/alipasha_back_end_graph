<?php

namespace App\Http\Controllers\Web;

use App\Enums\PlansDurationEnum;
use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class PricingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web')->only('store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::where('is_active', 1)->orderBy('sortable')->get()->chunk(3);
        return view('web.pricing', compact('plans'));
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
        $plan = Plan::findOrFail($request->plan_id);
        $price = $plan->is_discount ? $plan->discount : $plan->price;
        if (auth()->user()->getTotalBalance() < $price) {
            return back()->with('error', 'لا تملك رصيد كافي للإشتراك بالخطة');
        }
        \DB::beginTransaction();
        try {
            Balance::create([
                'user_id' => auth()->id(),
                'debit' => $price,
                'credit' => 0,
                'info' => "إشتراك بالخطة {$plan->name} ",

            ]);
            /**
             * @var $user User
             */
            $user = auth()->user();
            $duration = now();
            if ($plan->duration == PlansDurationEnum::FREE->value || $plan->duration == PlansDurationEnum::YEAR->value) {
                $duration = now()->addYear();
            } elseif ($plan->duration == PlansDurationEnum::MONTH->value) {
                $duration = now()->addMonth();
            }
            $user->plans()->syncWithPivotValues([$plan->id], ['subscription_date' => now(),
                'expired_date' => $duration], false);
            \DB::commit();
            return back()->with('success', 'تم الإشتراك بالخطة بنجاح');
        } catch (\Exception | \Error $e) {
            \DB::rollBack();
            return back()->with('error', $e->getMessage());
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
