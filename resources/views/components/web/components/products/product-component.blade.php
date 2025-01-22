<div class="col-md-12 ">
    <div class="card my-2 pt-3">
        <div class="d-flex flex-column justify-content-center align-items-start">
            <div class="d-flex flex-row px-5">

                <x-web.components.tools.image-component :src=" $product->user?->getImageForce()" class="image-rounded" :alt="$product->user?->name"/>
                <div class="d-flex flex-column">
                    <span class="h5">{{$product->user?->name}}</span>
                    <span class="text-muted fs-6">{{$product->city?->name}} - {{$product->category?->name}} - {{$product->sub1?->name}}</span>
                </div>

            </div>
            <div class="post-img-home px-3 rounded">
                <img class="rounded" src="{{$product->getImageForce()}}" alt="{{$product->name}}">
            </div>
        </div>
    </div>
</div>
