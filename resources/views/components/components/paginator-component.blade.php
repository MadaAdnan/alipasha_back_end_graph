

@if($paginator!=null)


    <nav class="bg-white rounded p-2">

            <div class="d-flex justify-content-center align-items-center">

                {{-- السابق --}}
                <span class=" {{ $paginator->onFirstPage() ? 'disabled' : 'text-red-accent' }}">
                    <a class=""
                       href="{{ $paginator->previousPageUrl() ?? '#' }}">
                        <i class=" {{ $paginator->onFirstPage() ? 'disabled' : 'text-red-accent' }} fa fa-angle-right"></i>
                    </a>
                </span>

                {{-- الأرقام --}}
                @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
                    <span class="mx-1 btn rounded py-1 px-2  {{ $paginator->currentPage() == $page ? 'btn-red-accent' : 'bg-white' }}">
                        <a class="" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </span>
                @endforeach

                {{-- التالي --}}
                <span class=" {{ $paginator->hasMorePages() ? 'text-red-accent' : 'disabled' }}">
                    <a class=""
                       href="{{ $paginator->nextPageUrl() ?? '#' }}">

                          <i class=" {{ $paginator->hasMorePages() ? 'text-red-accent' : 'disabled' }} fa fa-angle-left"></i>
                    </a>
                </span>

            </div>
    </nav>

@endif
