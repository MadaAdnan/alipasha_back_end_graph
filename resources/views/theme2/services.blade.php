@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
        <div class="row">

            <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-services-component :services="$categories"/>
                <x-components.filter-component route="{{route('services.index')}}" type="{{\App\Enums\CategoryTypeEnum::SERVICE->value}}"/>
            </div>
        </div>
    </div>

@endsection
