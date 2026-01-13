@props([
    'seller'=>null,
    'class'=>'',
    'iconSize'=>'',
    'image'=>null
])
@if($seller)
    <a class="cursor-pointer d-flex justify-content-start  align-items-center gap-1"
       href="{{route('seller.profile',$seller->id)}}">
        @if($image)
            <img src="{{$image}}" class="rounded-circle" alt="">
        @else
            <i class="fa fa-shop {{$iconSize}}"></i>
        @endif
        <span class="seller-name d-inline-block  {{$class??'fs-6'}}">  {{$seller->seller_name ?? $seller->name}}</span>
        @if($seller->is_verified==true)
            <i class="bi bi-patch-check-fill text-blue-accent {{$iconSize}}"></i>
        @endif
    </a>
@else
    <span></span>
@endif
