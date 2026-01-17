@props([
    'product'=>null
])
<div class="stat-item-modern">
    <i class="fas fa-heart @if(!auth()->check() ||!$product->is_like) text-gray  @endif"></i>
    <span>{{\App\Helpers\GlobalHelper::formatNumber($product->likes_count)}}</span>
</div>
