
@props(['list'])

<div {{$attributes->class(['col-md-3 d-none d-md-block'])}}>
   <div class="shadow-sm rounded">
       <div class="d-flex justify-content-center">
           <a class="btn btn-md w-50 m-auto d-inline-block btn-danger" href="">منشور جديد</a>
       </div>
       <ul class="nav flex-column">
           @isset($list)
               @foreach($list as $key=>$value)
                   <li class="nav-item">
                       <a class="nav-link active" aria-current="page" href="{{$value}}">{{$key}}</a>
                   </li>
               @endforeach
           @endisset
       </ul>
   </div>
</div>
