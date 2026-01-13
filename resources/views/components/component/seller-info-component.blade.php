@props([
    'seller'=>null
])
<div class="card card-body">
   <h6 class="text-gray fw-bold my-1">معلومات المعلن</h6>
    <x-components.seller-name-component :seller="$seller" :image="$seller->getImage()"/>
    <span class="small text-muted"><i class="fa fa-map-location"></i> {{$seller->address}}</span>
</div>
