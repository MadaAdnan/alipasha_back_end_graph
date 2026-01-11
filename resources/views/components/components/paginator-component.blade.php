

@if($paginator!=null)


    <nav class="bg-white rounded p-2">

            <div class="d-flex justify-content-center">

                {{-- السابق --}}
                <span class=" {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class=""
                       href="{{ $paginator->previousPageUrl() ?? '#' }}">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </span>

                {{-- الأرقام --}}
                @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
                    <span class=" rounded p-1 {{ $paginator->currentPage() == $page ? 'bg-gold' : 'bg-white' }}">
                        <a class="" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </span>
                @endforeach

                {{-- التالي --}}
                <span class=" {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class=""
                       href="{{ $paginator->nextPageUrl() ?? '#' }}">

                          <i class="fa fa-angle-left"></i>
                    </a>
                </span>

            </div>
    </nav>

@endif
