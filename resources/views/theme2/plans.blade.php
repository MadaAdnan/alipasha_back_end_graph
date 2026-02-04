@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">


            <!-- شبكة الخطط مع الأسهم -->
            <div class="col-md-12">
                <div class="plans-carousel-wrapper">
                    <!-- السهم الأيسر -->
                    <button class="plans-arrow plans-arrow-left" id="plansArrowLeft" title="السابق">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- شبكة الخطط -->
                    <div class="plans-grid-container">
                        <div class="plans-grid" id="plansGrid">
                            @forelse($plans as $plan)
                                @php
                                    $plansId=auth()->user()->plans->pluck('id')->toArray();
                                    $isActive=in_array($plan->id,$plansId);
                                    $isFeatured=$loop->iteration === 2;
                                @endphp
                                <x-components.plan-card-component
                                    :plan="$plan"
                                    :isActive="$isActive"
                                    :isFeatured="$isFeatured"
                                />
                            @empty
                                <div class="col-12">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>لا توجد خطط متاحة حالياً</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- السهم الأيمن -->
                    <button class="plans-arrow plans-arrow-right" id="plansArrowRight" title="التالي">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.getElementById('plansGrid');
            const leftArrow = document.getElementById('plansArrowLeft');
            const rightArrow = document.getElementById('plansArrowRight');
            const cardWidth = 300; // عرض البطاقة
            const gap = 15; // الفجوة بين البطاقات
            const scrollAmount = cardWidth + gap; // مقدار التمرير = بطاقة كاملة + فجوة

            // التمرير لليسار
            leftArrow.addEventListener('click', function() {
                grid.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // التمرير لليمين
            rightArrow.addEventListener('click', function() {
                grid.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            // تحديث حالة الأسهم - الأسهم لا تصبح معطلة (تمرير دوراني)
            function updateArrowsState() {
                // الأسهم تبقى مفعلة دائماً للتمرير الدوراني
                leftArrow.disabled = false;
                rightArrow.disabled = false;
            }

            // تحديث الأسهم عند التمرير
            grid.addEventListener('scroll', updateArrowsState);
            window.addEventListener('resize', updateArrowsState);

            // تحديث أولي
            updateArrowsState();
        });
    </script>
@endsection
