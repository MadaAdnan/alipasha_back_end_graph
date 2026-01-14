

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
                @foreach ($paginator->toArray()['links'] as $link)
                    @if ($link['url'])
                        <span class="mx-1 btn rounded py-1 px-2 {{ $link['active'] ? 'btn-red-accent' : 'bg-white' }}">
            <a href="{{ $link['url'] }}">
                {!! $link['label'] !!}
            </a>
        </span>
                    @endif
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
