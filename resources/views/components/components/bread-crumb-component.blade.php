@props([
    'first'=>'الرئيسية',
    'urlFirst'=>route('index'),
    'iconFirst'=>'fas fa-home',
    'categories'=>[],
    'class'=>'bg-white'
])
    <nav class="breadcrumb  rounded  @if($class) {{$class}} @else p-2 @endif">
        <ol class="breadcrumb m-0 p-0">
            <li class="breadcrumb-item"> <a href="{{$urlFirst}}"><i class="{{$iconFirst}}"></i> {{$first}}</a></li>
            @foreach($categories as $category)
                @if(!isset($category['name']) || (isset($category['name']) && $category['name']==''))
                    @continue
                @endif
                <li class="breadcrumb-item {{$category['class']??''}}" @if(isset($category['class']) && $category['class']=='active') aria-current="page" @endif><a href="{{$category['url']??'#'}}">@if(isset($category['icon'])) <i class="{{$category['icon']}}"></i> @endif {{$category['name']}}</a></li>
            @endforeach
        </ol>




    </nav>

