@props([
    'product' => null,
])
<div class="position-relative d-inline-block">
    <!-- صورة المنتج -->
    <img src="{{ $product->getImage() }}" alt="Product Image" class="img-fluid rounded" style="display:block;">

    <!-- زر مشاهدة الفيديو -->
    <a href="{{ $product->video }}" target="_blank"
       class="position-absolute top-50 start-50 translate-middle btn btn-danger">
        <i class="fa fa-play"></i> مشاهدة الفيديو
    </a>
</div>
