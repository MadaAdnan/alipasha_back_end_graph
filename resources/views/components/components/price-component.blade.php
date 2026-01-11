@props([
    'price'=>null,
'discount'=>null,
'isDiscount'=>false,
])
<div class="d-flex gap-1">
    @if($isDiscount)
        <del class="text-gray fs-6">{{$price}} $</del>
        <span class="text-red-accent fw-bold fs-4">{{$discount}} $</span>
    @else
        <span class="text-red-accent fw-bold fs-4">{{$price}} $</span>
    @endif
</div>
