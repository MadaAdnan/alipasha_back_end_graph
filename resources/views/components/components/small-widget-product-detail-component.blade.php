@props([
    'title'=>null,
'classTitle'=>'text-gray fs-4',
'icon'=>null,
'info'=>null,
'classInfo'=>'text-gray fs-6',
'class'=>''
])
@php
$classData=trim($class).' w-100 d-flex flex-column gap-1 justify-content-center align-items-center'
 @endphp
<div class="{{$classData}}">
    <h4 class="{{$classTitle}}">{{ $title }}</h4>
    <i class="{{ $icon }}"></i>
    <span class="{{$classInfo}}">{{ $info }}</span>
</div>
