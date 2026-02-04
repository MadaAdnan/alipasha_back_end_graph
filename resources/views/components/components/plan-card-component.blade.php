@props(['plan', 'isActive' => false, 'isFeatured' => false])

<div class="plan-card-premium @if($isActive) plan-card-active @endif @if($isFeatured) plan-card-featured @endif">
    <!-- Badge للخطة المميزة -->
    @if($isFeatured || $plan->type === 'premium')
        <div class="plan-badge">
            <i class="fas fa-star"></i>
            <span>الأكثر شهرة</span>
        </div>
    @endif

    <!-- رأس البطاقة -->
    <div class="plan-header">
        <h3 class="plan-title">{{ $plan->name ?? 'خطة بدون اسم' }}</h3>
        <p class="plan-duration">
            {{\App\Enums\PlansDurationEnum::tryFrom($plan->duration)?->getLabel()}}
        </p>
    </div>

    <!-- قسم السعر -->
    <div class="plan-price-section">
        @if($plan->is_discount)
            <div class="price-wrapper">
                <span class="price-original">{{ $plan->price }} $</span>
                <span class="price-current">{{ $plan->discount }} $</span>
            </div>
            <div class="discount-badge">
                <span>توفير {{ round(((($plan->price - $plan->discount) / $plan->price) * 100)) }}%</span>
            </div>
        @else
            <div class="price-wrapper">
                <span class="price-current">{{ $plan->price }} $</span>
            </div>
        @endif
    </div>

    <!-- الوصف -->
    <p class="plan-description">{{ $plan->info }}</p>

    <!-- قائمة الميزات -->
    <div class="plan-features">
        <ul class="features-list">
            @foreach($plan->items as $item)
                <li class="feature-item @if($item['active']) feature-active @else feature-inactive @endif">
                    <span class="feature-icon">
                        @if($item['active'])
                            <i class="fas fa-check-circle"></i>
                        @else
                            <i class="fas fa-times-circle"></i>
                        @endif
                    </span>
                    <span class="feature-text">{{ $item['item'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- زر الاشتراك -->
    <div class="plan-action">
        @if($isActive)
            <button class="btn btn-subscribed" disabled>
                <i class="fas fa-check"></i>
                <span>تم الإشتراك</span>
            </button>
        @else
            <form action="{{route('plans.store')}}" method="post" class="w-100">
                @csrf
                <input type="hidden" name="planId" value="{{$plan->id}}">
                <button type="submit" class="btn btn-subscribe">
                    <span>إشترك الآن</span>
                    <i class="fas fa-arrow-left"></i>
                </button>
            </form>
        @endif
    </div>
</div>

