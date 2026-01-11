@props([
    'first'=>'الرئيسية',
    'urlFirst'=>route('index'),
    'iconFirst'=>'fas fa-home',
    'categories'=>[],
])
<div class="container">
    <div class="breadcrumb bg-white">
        <a href="{{$urlFirst}}"><i class="{{$iconFirst}}"></i> {{$first}}</a>
        @foreach($categories as $category)
            <a href="{{$category['url']??'#'}}">@if(isset($category['icon'])) <i class="{{$category['icon']}}"></i> @endif {{$category['name']}}</a>
        @endforeach


    </div>
</div>
