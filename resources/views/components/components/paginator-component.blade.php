

@if($paginator!=null)

    <nav class="pagination-nav">
        <div class="pagination-container">

            {{-- السابق --}}
            <a class="pagination-btn pagination-prev {{ $paginator->onFirstPage() ? 'disabled' : '' }}"
               href="{{ $paginator->withQueryString()->previousPageUrl() ?? '#' }}"
               {{ $paginator->onFirstPage() ? 'disabled' : '' }}>
                <i class="fa fa-chevron-right"></i>
            </a>

            {{-- الأرقام --}}
            <div class="pagination-numbers">
                @foreach ($paginator->withQueryString()->links()->elements[0] ?? [] as $page => $url)
                    <a class="pagination-number {{ $paginator->currentPage() == $page ? 'active' : '' }}"
                       href="{{ $url }}">
                        {{ $page }}
                    </a>
                @endforeach
            </div>

            {{-- التالي --}}
            <a class="pagination-btn pagination-next {{ $paginator->hasMorePages() ? '' : 'disabled' }}"
               href="{{ $paginator->withQueryString()->nextPageUrl() ?? '#' }}"
               {{ !$paginator->hasMorePages() ? 'disabled' : '' }}>
                <i class="fa fa-chevron-left"></i>
            </a>

        </div>
    </nav>

@endif
