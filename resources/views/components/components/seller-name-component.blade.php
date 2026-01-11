@props([
    'seller'=>null,
    'class'=>''
])
@if($seller)
<a class="cursor-pointer d-flex justify-content-start  align-items-center gap-1" href="{{route('seller.profile',$seller->id)}}">
    <i class="fa fa-shop"></i>
  <span class="seller-name d-inline-block  flex-grow-1 {{$class??'fs-6'}}">  {{$seller->seller_name ?? $seller->name}}</span>
    @if($seller->is_verified==true)
        <i class="bi bi-patch-check-fill text-blue-accent"></i>
    @endif
</a>
@else
<span></span>
@endif
