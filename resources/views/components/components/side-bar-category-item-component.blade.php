@props([
    'category'=>null
])
@php
$dataCategory=$category;
 @endphp
<a class="cursor-pointer " href="{{route('category.show',$dataCategory?->id)}}">
<div class="d-flex justify-content-between py-1">
    <div class="side-bar-category-item-icon">
        <i class="fa-regular fa-circle-dot"></i>
        <span>  {{ $dataCategory?->name }}</span>
    </div>
    <div class="side-bar-category-item-title">
        <i class="fa fa-angle-left"></i>
    </div>
</div>
</a>
