@props(['list'])

<div {{$attributes->class(['col-md-3 d-none d-md-block'])}}>
    <div class="shadow-sm rounded mt-3 mb-1 py-2 bg-white">
        <div class="d-flex justify-content-center my-3">
            <a class="btn btn-md w-50 m-auto d-inline-block btn-danger" href="">منشور جديد</a>
        </div>
        <ul class="nav flex-column px-3">
            @isset($list)
                @foreach($list as $key=>$value)
                    <li class="nav-item my-1 py-2 card ">
                        @if(is_array($value))
                            <a class="nav-link active" aria-current="page" href="{{$value['url']}}">
                        @else
                                    <a class="nav-link active" aria-current="page" href="{{$value}}">
                            @endif
                        <div class="d-flex justify-content-start py-1 px-5 align-items-center">
                            @if(is_array($value))
                                <span class="badge text-bg-danger rounded-circle mx-5 products-count d-flex justify-content-center align-items-center  ">{{$value['count']}}</span>
                              <span>{{$key}}</span>

                            @else
                                <span>{{$key}}</span>
                                @endif


                        </div>
                                    </a>
                    </li>
                @endforeach
            @endisset
        </ul>
    </div>
</div>
