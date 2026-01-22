@extends('theme2.layouts.master')
@section('content')
    <div class="container mb-2 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="row justify-content-center">
                <div class="col-md-4 col-6">
                    <x-components.small-widget-product-detail-component classTitle="fs-4"
                                                                        classInfo="fs-4"
                                                                        class="bg-white rounded my-1 py-2" title="الخدمات المنشورة" icon="fa-solid fa-file-signature fs-4" info="{{\App\Helpers\GlobalHelper::formatNumber($services_count)}}"/>
                </div>
                <div class="col-md-4 col-6">
                    <x-components.small-widget-product-detail-component title="عدد المشاهدات" icon="fa fa-eye fs-4" info="{{\App\Helpers\GlobalHelper::formatNumber($views)}}" classTitle="fs-4"
                                                                        classInfo="fs-4"
                                                                        class="bg-white rounded my-1 py-2"/>
                </div>
                <div class="col-md-4 col-6">
                    <x-components.small-widget-product-detail-component title="المزودين بالمعلومات" icon="fa fa-users fs-4" info="{{\App\Helpers\GlobalHelper::formatNumber($sellers)}}" classTitle="fs-4"
                                                                        classInfo="fs-4"
                                                                        class="bg-white rounded my-1 py-2"/>
                </div>
            </div>
        </div>
        </div>
        <div class="row">

            <div class="col-md-3  my-2 sticky-col">
                <x-components.side-bar-services-component :services="$categories"/>
                <x-components.filter-component :showPrice="false" :showTextSearch="true" route="{{route('services.index')}}" type="{{\App\Enums\CategoryTypeEnum::SERVICE->value}}"/>
            </div>
            <div class="col-md-9">
                @if($services->count()>0)
                <x-components.services-component :services="$services->items()"/>
                <x-components.paginator-component :paginator="$services"/>
                @else
                    <div class="alert alert-danger">
                        لا يوجد خدمات
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
