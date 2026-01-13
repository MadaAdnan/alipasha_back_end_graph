@props([
    'seller'=>null
])
<div class="card card-body">
   <h6 class="text-gray fw-bold my-1">معلومات المعلن</h6>
    <x-components.seller-name-component :seller="$seller" :image="$seller->getImage()"/>
    <span class="small text-muted"><i class="fa fa-location-dot"></i> {{$seller->address}}</span>
    <div class="divider my-1 "></div>
    <div class="d-flex justify-content-center">
        <form action="{{route('communities.store')}}">
            @csrf
            <button class="btn btn-sm btn-outline-primary">
                <i class="fa fa-chat"></i>
                <span class="small d-none d-md-inline-block mx-1">
                   تحدث معه
                </span>
            </button>
        </form>
        <form action="{{route('/')}}">
            @csrf
            <button class="btn btn-sm btn-outline-primary">
                <i class="fa-brands fa-whatsapp"></i>
                <span class="small d-none d-md-inline-block mx-1">
                   واتس آب
                </span>
            </button>
        </form>
    </div>
</div>
