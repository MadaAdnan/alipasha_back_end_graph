@props([
    'seller'=>null
])
<div class="card card-body">
   <h4>معلومات المعلن</h4>
    <x-components.seller-name-component :seller="$seller" :image="$seller->getImage()"/>
</div>
