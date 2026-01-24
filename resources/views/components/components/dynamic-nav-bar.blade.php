<nav class="navbar navbar-expand-lg bg-white border-top">
    <div class="container">


        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @foreach($navs as $nav)
                    @php
                        $url=$nav->url;
                        if(!Str::startsWith($nav->url,['https://','http://','//'])){
                            $url=url($nav->url);
                        }

                    @endphp
                    <li class="nav-item">

                        <a class="nav-link active" aria-current="page" href="{{$url}}"> <i class="{{$nav->icon}}"></i>{{$nav->title}}</a>
                    </li>
                @endforeach


            </ul>
        </div>
    </div>
</nav>
