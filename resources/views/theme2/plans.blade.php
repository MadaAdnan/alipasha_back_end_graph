@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">الخطط</h1>
            </div>


            <div class="plans-wrapper d-flex flex-wrap justify-content-center gap-4">
                @forelse($plans as $plan)
                    <div class="plan-card border rounded shadow-sm p-3 text-center flex-fill" style="min-width:250px; max-width:300px;">

                        <h5 class="plan-name mb-2">{{ $plan->name ?? 'خطة بدون اسم' }}</h5>


                            <p class="plan-price mb-2 h5 text-red">

                               @if($plan->is_discount)
                                  <del class="text-muted small"> <sup>{{ $plan->price }} $</sup></del>
                                {{ $plan->discount }} $
                               @else
                                    {{ $plan->price }} $
                               @endif
                                /{{\App\Enums\PlansDurationEnum::tryFrom($plan->duration)?->getLabel()}}
                            </p>



                            <p class="plan-description mb-3 text-muted" style="min-height:50px;">
                                {{ $plan->info }}
                            </p>



                            <button class="btn btn-outline-primary w-100" disabled>
                                الاشتراك
                            </button>

                    </div>
                @empty
                    <p class="text-center w-100">لا توجد خطط متاحة حالياً</p>
                @endforelse
            </div>

            <style>
                .plan-card:hover {
                    transform: translateY(-5px);
                    transition: all 0.3s ease;
                    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
                }
            </style>

        </div>
    </div>
@endsection
