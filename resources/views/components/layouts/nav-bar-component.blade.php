<nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{route('index')}}">
            <img class="logo" src="{{$setting?->getFirstMediaUrl('logo')}}" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li>
                    <form class="d-flex position-relative" role="search">
                        <input class="form-control me-2" type="search" placeholder="بحث" aria-label="Search"/>

                        <button class="transparent position-absolute search-icon-navbar"><i class="fa fa-search"></i></button>
                    </form>
                </li>
@guest()
                <li class="nav-item">
                    <a class="nav-link" href="{{route('login')}}"><i class="fa fa-user"></i> تسجيل الدخول </a>
                </li>

                @endguest
                @auth()
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-user"></i> {{auth()->user()->name}} </a>
                </li>
                <li class="nav-item">
                    <form action="{{route('logout')}}" method="post">
                        @csrf
                        @method('POST')
                        <button class="btn btn-danger">تسجيل الخروج</button>
                    </form>
                </li>
                @endauth
            </ul>

        </div>
    </div>
</nav>
