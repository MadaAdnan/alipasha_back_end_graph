@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">الخطط</h1>
            </div>


            <div class="plans-wrapper d-flex flex-wrap justify-content-center gap-4 position-relative ">
                @forelse($plans as $plan)
                    <div class="plan-card  border rounded shadow-sm p-3 text-center flex-fill" style="min-width:250px; max-width:300px;">

                        <h5 class="plan-name mb-2">{{ $plan->name ?? 'خطة بدون اسم' }}</h5>


                            <p class="plan-price mb-2 h5 text-red">

                               @if($plan->is_discount)
                                  <del class="text-muted small"> <sup>{{ $plan->price }} $</sup></del>
                                {{ $plan->discount }} $
                               @else
                                    {{ $plan->price }} $
                               @endif
                                   / <sub class="text-muted fs-6"> {{\App\Enums\PlansDurationEnum::tryFrom($plan->duration)?->getLabel()}}</sub>
                            </p>



                            <p class="plan-description mb-3 text-muted" style="min-height:50px;">
                                {{ $plan->info }}
                            </p>
                      <div class="d-flex flex-column h-100">
                          <ul class="list-unstyled w-100 mb-3 ps-0 pe-0 flex-grow-1">
                              @foreach($plan->items as $item)
                                  <li class="d-flex align-items-center mb-1 border border-1 rounded p-1">
                                      @if($item['active'])

                                          <i class="fa-regular fa-circle-check text-success fs-5 me-1"></i>
                                      @else

                                          <i class="fa-solid fa-circle-xmark text-danger fs-5 me-1"></i>
                                      @endif
                                      <span class="flex-grow-1 text-end px-1 text-dark" >{{ $item['item'] }}</span>
                                  </li>
                              @endforeach
                          </ul>
@php
$plansId=auth()->user()->plans->pluck('id')->toArray();
$isActive=in_array($plan->id,$plansId);
 @endphp
@if($isActive)
                              <button class="btn btn-outline-secondary w-100 mt-auto" disabled>
                                  تم الإشتراك
                              </button>
                          @else
                              <button class="btn btn-red-accent w-100 mt-auto" >
                                   إشترك الآن
                              </button>
@endif

                      </div>

                    </div>
                @empty
                    <p class="text-center w-100">لا توجد خطط متاحة حالياً</p>
                @endforelse
            </div>


        </div>
    </div>
@endsection
@push('css')
    <style>

        .plan-card .btn {
            position: relative;       /* أو absolute داخل container */
            bottom: 0px; /* يحاول استخدام النقطة المرجعية */
        }
        .plan-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
    </style>
@endpush
