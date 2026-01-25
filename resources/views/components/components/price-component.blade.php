@props([
    'price'=>null,
'discount'=>null,
'isDiscount'=>false,
'class'=>null
])
<div class="d-flex gap-1 @if($class) {{$class}} @endif">
    @if($isDiscount)
        <del class="text-gray fs-7">{{$price}} $</del>
        <span class="text-red-accent fw-bold fs-6">{{$discount}} $</span>
    @else
        <span class="text-red-accent fw-bold fs-6">{{$price}} $</span>
    @endif
</div>
