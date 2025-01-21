@props(['list'])

<div {{$attributes->class(['col-md-3 d-none d-md-block'])}}>
    <div class="shadow-sm rounded mt-3 mb-1 py-2 bg-white">
        <div class="d-flex justify-content-center my-3">
            <a class="btn btn-md w-50 m-auto d-inline-block btn-danger" href="">منشور جديد</a>
        </div>
        <ul class="nav flex-column px-3">
            @isset($list)
                @foreach($list as $key=>$value)
                    <li class="nav-item my-1 card ">
                        <div class="d-flex justify-content-start py-1 px-1 align-items-center">
                            @if(is_array($value))
                                <span class="badge text-bg-danger rounded-circle  ">{{$value['count']}}</span>
                                <a class="nav-link active" aria-current="page" href="{{$value['url']}}">{{$key}}</a>

                            @else
                                <a class="nav-link active" aria-current="page" href="{{$value}}">{{$key}}</a>
                                @endif


                        </div>

                    </li>
                @endforeach
            @endisset
        </ul>
    </div>
</div>
