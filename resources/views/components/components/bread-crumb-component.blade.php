@props([
    'first'=>'الرئيسية',
    'urlFirst'=>route('index'),
    'iconFirst'=>'fas fa-home',
    'categories'=>[],
    'class'=>null
])
    <nav class="breadcrumb bg-white rounded p-2 @if($class) {{$class}} @endif">
        <ol class="breadcrumb align-items-center">
            <li class="breadcrumb-item"> <a href="{{$urlFirst}}"><i class="{{$iconFirst}}"></i> {{$first}}</a></li>
            @foreach($categories as $category)
                @if(!isset($category['name']) || (isset($category['name']) && $category['name']==''))
                    @continue
                @endif
                <li class="breadcrumb-item {{$category['class']??''}}" @if(isset($category['class']) && $category['class']=='active') aria-current="page" @endif><a href="{{$category['url']??'#'}}">@if(isset($category['icon'])) <i class="{{$category['icon']}}"></i> @endif {{$category['name']}}</a></li>
            @endforeach
        </ol>




    </nav>

