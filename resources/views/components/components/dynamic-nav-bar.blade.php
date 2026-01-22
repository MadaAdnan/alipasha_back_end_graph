<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
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
                    <li class="nav-item">
                        <i class="{{$nav->icon}}"></i>
                        <a class="nav-link active" aria-current="page" href="{{$url}}">{{$nav->title}}</a>
                    </li>
                @endforeach


            </ul>
        </div>
    </div>
</nav>
