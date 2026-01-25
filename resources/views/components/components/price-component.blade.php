@props([
    'price'=>null,
    'discount'=>null,
    'isDiscount'=>false,
    'class'=>null
])
<div class="price-container @if($class) {{$class}} @endif">
    @if($isDiscount)
        <div class="price-wrapper">
            <span class="price-original">{{$price}} $</span>
            <span class="price-discount-badge">
                @php
                    $discountPercent = round((($price - $discount) / $price) * 100);
                @endphp
                -{{ $discountPercent }}%
            </span>
        </div>
        <span class="price-current">{{$discount}} $</span>
    @else
        <span class="price-current">{{$price}} $</span>
    @endif
</div>

<style>
    .price-container {
        display: flex;
        flex-direction: column;
        gap: 0;
        align-items: flex-start;
    }

    .price-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        height: 28px;
        margin-top: -8px;
    }

    .price-original {
        font-size: 14px;
        color: #6c757d;
        text-decoration: line-through;
        font-weight: 500;
        white-space: nowrap;
        line-height: 1;
    }

    .price-discount-badge {
        background: linear-gradient(135deg, #e30613 0%, #ff5f57 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(227, 6, 19, 0.3);
        white-space: nowrap;
        line-height: 1;
    }

    .price-current {
        font-size: 28px;
        font-weight: 700;
        color: #e30613;
        letter-spacing: -0.5px;
        white-space: nowrap;
        line-height: 1;
    }

    @media (max-width: 768px) {
        .price-wrapper {
            height: 24px;
            gap: 8px;
        }

        .price-current {
            font-size: 24px;
        }

        .price-original {
            font-size: 13px;
        }

        .price-discount-badge {
            font-size: 11px;
            padding: 3px 8px;
        }
    }
</style>
