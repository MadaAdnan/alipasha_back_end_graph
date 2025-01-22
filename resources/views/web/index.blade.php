<x-web.components.layouts.layout-component>
    <x-slot name="rightSidebar">
        <x-web.components.layouts.right-side-bar-component name="name"/>
    </x-slot>

    <x-slot name="leftSidebar">

        <x-web.components.layouts.left-side-bar-component name="name" :list="$categories"/>
    </x-slot>
    <div class="container">
        <div class="row">
    @foreach($products as $product)

                <x-web.components.products.product-component :product="$product" key="{{$product->id}}"/>

    @endforeach
        </div>
        <x-web.components.tools.paginate-links :next="$products->nextPageUrl()" :back="$products->previousPageUrl()" current="{{$products->currentPage()}}" :hasMore="$products->hasMorePages()"/>
    </div>
</x-web.components.layouts.layout-component>


