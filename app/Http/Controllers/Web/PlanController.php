<?php

namespace App\Http\Controllers\Web;

use App\Enums\PlansDurationEnum;
use App\Exceptions\GraphQLExceptionHandler;
use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use GraphQL\Error\Error;
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
        $planId = $request->planId;
        $plan = Plan::where('is_active',1)->find($planId);
        /**
         * @var $user User
         */
        $user = auth()->user();
        try {
            if (!$plan) {
               return back()->with('error', 'الخطة غير متوفرة');
            }

            $balance = $user->getTotalBalance();
            $planPrice = $plan->is_discount ? $plan->discount : $plan->price;

            switch ($plan->duration) {
                case PlansDurationEnum::MONTH->value:
                    $expiredDate = now()->addMonth();
                    break;
                case PlansDurationEnum::YEAR->value:
                    $expiredDate = now()->addYear();
                    break;
                default :
                    $expiredDate = now()->addYear();
            }
            $subscription_date = now();
            if ($balance < $planPrice) {
                return back()-> with('error','لا تملك رصيد كافي');
            }
            $subscribePlan = $user->plans()->where('plans.id', $planId)->first();
            if ($subscribePlan) {
                $oldDate = Carbon::parse($subscribePlan->pivot->expired_date);
                $subscription_date = $subscribePlan->pivot->subscription_date;
                if ($oldDate->greaterThanOrEqualTo(now())) {
                    $expiredDate = $oldDate->addMonth();
                }
            }
            \DB::beginTransaction();
            try {
                $user->plans()->syncWithPivotValues($planId, ['expired_date' => $expiredDate, 'subscription_date' => $subscription_date], false);
                if ($plan->is_validate) {
                    $user->update(['is_verified' => true, 'verified_account_date' => $expiredDate]);
                }
                if ($plan->special_store) {
                    $user->update(['is_special' => true]);
                }
                Balance::create([
                    'debit' => $planPrice,
                    'credit' => 0,
                    'user_id' => $user->id,
                    'info' => "إشتراك بخطة {$plan->name} حتى تاريخ  {$expiredDate->format('Y-m-d')}"
                ]);
                \DB::commit();
                return back()->with('success', 'تمت العملية بنجاح');
            } catch (\Exception|\Error $e) {
                \DB::rollBack();
                return back()->with('error',$e->getMessage());
            }

        } catch (\Exception|Error $e) {
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
