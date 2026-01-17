@props([
    'product'=>null
])
<div class="stat-item-modern">
    <i class="fas fa-heart like-icon @if(!auth()->check() || !$product->is_like) text-gray @endif"
       data-user-id="{{auth()->id()}}"
       data-product-id="{{$product->id}}"
       onclick="toggleLike({{auth()->id()}}, {{$product->id}})"></i>
    <span id="likes-count-{{$product->id}}">{{\App\Helpers\GlobalHelper::formatNumber($product->likes_count)}}</span>
</div>

<script>
function toggleLike(userId, productId) {
   console.log("OK OK")
}
</script>
