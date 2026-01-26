@props([
    'class'=>null
])
<nav class="navbar navbar-expand-lg bg-white border-top {{$class}}">
    <div class="container">

        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @foreach($navs as $nav)
                    @php
                        $url=$nav->url;
                        if(!Str::startsWith($nav->url,['https://','http://','//'])){
                            $url=url($nav->url);
                        }

                    @endphp
                    <li class="nav-item nav-divider">
                        <a class="nav-link active text-muted" aria-current="page" href="{{$url}}"> <i class="{{$nav->icon}}"></i><span class="d-inline-block mx-1">{{$nav->title}}</span></a>
                    </li>
                @endforeach

                @auth()
                    <!-- Divider -->
                    <li class="nav-item">
                        <hr class="dropdown-divider my-2">
                    </li>

                    <!-- Logout Button - Mobile Only -->
                    <li class="nav-item d-lg-none">
                        <form action="{{route('logout')}}" method="post" class="w-100">
                            @csrf
                            @method('POST')
                            <button type="submit" class="nav-link text-danger w-100 text-start">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="d-inline-block mx-1">تسجيل الخروج</span>
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
