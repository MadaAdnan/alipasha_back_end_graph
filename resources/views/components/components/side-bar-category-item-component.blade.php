@props([
    'category'=>null,
    'categoryId'=>null
])
@php
$dataCategory=$category;
$category_id=$categoryId??null;
 @endphp


        <dt>
            <a class="cursor-pointer " href="{{route('category.show',$dataCategory?->id)}}">
                <div class="d-flex justify-content-between py-1">
                    <div class="side-bar-category-item-icon text-ellipsis">
                        <i class="fa-regular fa-circle-dot"></i>
                        <span class="">  {{ $dataCategory?->name }}</span>
                    </div>
                    <div class="side-bar-category-item-title">
                        <i class="fa fa-angle-left"></i>
                    </div>
                </div>
            </a>
        </dt>
       @if($categoryId==$dataCategory->id)
        @foreach($dataCategory->children as $child)
            <dd>
                <a class="cursor-pointer " href="{{route('category.show',$child?->id)}}">
                    <div class="d-flex justify-content-between py-1">
                        <div class="side-bar-category-item-icon text-ellipsis">
                            <i class="fa-regular fa-circle-dot"></i>
                            <span class="">  {{ $child?->name }}</span>
                        </div>
                        <div class="side-bar-category-item-title">
                            <i class="fa fa-angle-left"></i>
                        </div>
                    </div>
                </a>
            </dd>
        @endforeach
           @endif



