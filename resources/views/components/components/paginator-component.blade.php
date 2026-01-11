

@if($paginator!=null)


    <nav class="bg-white rounded p-2">

            <ul class=" justify-content-center">

                {{-- السابق --}}
                <li class=" {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class=""
                       href="{{ $paginator->previousPageUrl() ?? '#' }}">
                        <i class="fa fa-angle-left"></i>
                    </a>
                </li>

                {{-- الأرقام --}}
                @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
                    <li class=" {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                        <a class="" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </li>
                @endforeach

                {{-- التالي --}}
                <li class=" {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class=""
                       href="{{ $paginator->nextPageUrl() ?? '#' }}">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>

            </ul>
    </nav>

@endif
