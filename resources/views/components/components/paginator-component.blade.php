@props(['paginator'])

@if($paginator!=null)


    <nav>

            <ul class="pagination justify-content-center">

                {{-- السابق --}}
                <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link"
                       href="{{ $paginator->previousPageUrl() ?? '#' }}">
                        السابق
                    </a>
                </li>

                {{-- الأرقام --}}
                @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
                    <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </li>
                @endforeach

                {{-- التالي --}}
                <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link"
                       href="{{ $paginator->nextPageUrl() ?? '#' }}">
                        التالي
                    </a>
                </li>

            </ul>
    </nav>
@else
    <h2>ERROR</h2>
@endif
