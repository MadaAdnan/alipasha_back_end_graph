@php use Illuminate\Pagination\UrlWindow; @endphp
@props(['paginator'])


@if ($paginator && ($paginator instanceof \Illuminate\Pagination\Paginator || $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator)&& $paginator->hasPages())
    @php

        $elements = UrlWindow::make($paginator)->elements;
    @endphp

    <nav>
        <ul class="pagination justify-content-center">

            {{-- السابق --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}">
                    السابق
                </a>
            </li>

            {{-- الأرقام --}}
            @foreach ($elements as $element)
                {{-- ثلاث نقاط --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- روابط --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- التالي --}}
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}">
                    التالي
                </a>
            </li>

        </ul>
    </nav>
@endif
