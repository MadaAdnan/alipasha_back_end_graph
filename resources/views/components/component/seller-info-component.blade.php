@props([
    'seller'=>null
])
<div class="card card-body">
   <h6 class="text-gray">معلومات المعلن</h6>
    <x-components.seller-name-component :seller="$seller" :image="$seller->getImage()"/>
</div>
