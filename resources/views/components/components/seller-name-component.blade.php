@props([
    'seller'=>null
])
@if($seller)
<div class="">
    @if($seller->is_verified!==true)
        <i class="bi bi-patch-check-fill"></i>
    @endif
  <span>  {{$seller->seller_name ?? $seller->name}}</span>
</div>
@else
<span></span>
@endif
