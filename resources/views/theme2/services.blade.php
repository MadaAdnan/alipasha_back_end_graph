@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
        <div class="row">
            <div class="col-md-2 col-6">
                <x-components.small-widget-product-detail-component title="الخدمات المنشورة" icon="" info="{{$services_count}}"/>
            </div>
            <div class="col-md-2 col-6">
                <x-components.small-widget-product-detail-component title="عدد المشاهدات" icon="" info="{{$views}}"/>
            </div>
            <div class="col-md-2 col-6">
                <x-components.small-widget-product-detail-component title="المزودين بالمعلومات" icon="" info="{{$sellers}}"/>
            </div>
        </div>
        <div class="row">

            <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-services-component :services="$categories"/>
                <x-components.filter-component route="{{route('services.index')}}" type="{{\App\Enums\CategoryTypeEnum::SERVICE->value}}"/>
            </div>
            <div class="col-md-9">
                <x-components.services-component :services="$services->items()"/>
                <x-components.paginator-component :paginator="$services"/>
            </div>
        </div>
    </div>

@endsection
