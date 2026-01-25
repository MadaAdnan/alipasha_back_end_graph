<div class="related-products-section">
    <div class="categories-header">
        <i class="fa-solid fa-link"></i>
        <span>منتجات ذات صلة</span>
    </div>

    @if($products)
        <div class="related-products-grid">
            @foreach($products as $product)
                <div class="related-product-item">
                    <x-components.product-container-item-component :product="$product"/>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-related-products">
            <i class="fa-solid fa-inbox"></i>
            <p>لا توجد منتجات ذات صلة</p>
        </div>
    @endif
</div>

<style>
    .related-products-section {
        margin-top: 30px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .related-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
        padding: 30px;
    }

    .related-product-item {
        transition: all 0.3s ease;
    }

    .related-product-item:hover {
        transform: translateY(-5px);
    }

    .no-related-products {
        padding: 60px 30px;
        text-align: center;
        color: #999;
    }

    .no-related-products i {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 15px;
        display: block;
    }

    .no-related-products p {
        margin: 0;
        font-size: 16px;
    }

    @media (max-width: 992px) {
        .related-products-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            padding: 20px;
        }
    }

    @media (max-width: 768px) {
        .related-products-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            padding: 15px;
        }
    }
</style>
