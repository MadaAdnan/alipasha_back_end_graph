@props([
    'category'=>null,
    'categoryId'=>null
])
@php
$dataCategory=$category;
 @endphp
@if($categoryId)
    <dl>
        <dt>
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
        </dt>
        @foreach($dataCategory->children as $child)
            <dd>
                <a class="cursor-pointer " href="{{route('category.show',$child?->id)}}">
                    <div class="d-flex justify-content-between py-1">
                        <div class="side-bar-category-item-icon">
                            <i class="fa-regular fa-circle-dot"></i>
                            <span>  {{ $child?->name }}</span>
                        </div>
                        <div class="side-bar-category-item-title">
                            <i class="fa fa-angle-left"></i>
                        </div>
                    </div>
                </a>
            </dd>
        @endforeach
    </dl>
    @else
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
@endif

