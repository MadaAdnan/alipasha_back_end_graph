@props([
    'route'=>null,
    'categoryId'=>null,
    'sellerId'=>null,
    'type'=>null,
    'showPrice'=>true,
    'showTextSearch'=>true
])
<form class="filter-container" action="{{$route??route('search.index')}}" method="get">
    <div class="filter-header">
        <i class="fas fa-sliders-h"></i>
        <span>الفلاتر</span>
    </div>
    <input type="hidden" name="category_id" value="{{$categoryId}}">
    <input type="hidden" name="seller_id" value="{{$sellerId}}">
    <input type="hidden" name="type" value="{{$type}}">
    @if($showTextSearch)
        <div class="filter-group">
            <label class="filter-label" for="Search">بحث</label>
            <input class="filter-input" id="" name="q" placeholder="ابحث عن خدمة"/>
        </div>
    @endif
    {{-- المحافظة --}}
    <div class="filter-group">
        <label class="filter-label" for="governorateSelect">المحافظة</label>
        <select class="filter-select" id="governorateSelect" name="city_id">
            <option value="">اختر المحافظة</option>
            @foreach($governorates as $gov)
                <option value="{{ $gov->id }}">{{ $gov->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- المدينة --}}
    <div class="filter-group">
        <label class="filter-label" for="citySelect">المدينة</label>
        <select class="filter-select" id="citySelect" disabled name="area_id">
            <option value="">اختر المدينة</option>
        </select>
    </div>
    @if($showPrice)
        {{-- السعر من والسعر إلى --}}
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label" for="priceFrom">السعر من</label>
                <input type="number" class="filter-input" id="priceFrom" placeholder="0" name="price_from">
            </div>

            <div class="filter-group">
                <label class="filter-label" for="priceTo">السعر إلى</label>
                <input type="number" class="filter-input" id="priceTo" placeholder="0" name="price_to">
            </div>
        </div>
    @endif
    <button type="submit" class="filter-submit-btn">
        <i class="fas fa-search"></i> بحث
    </button>
</form>

<script>
    const cities = @json($cities);

    function loadCities() {
        let govSelect = document.getElementById('governorateSelect');
        let govId = govSelect.value;
        let citySelect = document.getElementById('citySelect');

        citySelect.innerHTML = '<option value="">اختر المدينة</option>';

        if (!govId) {
            citySelect.disabled = true;
            return;
        }

        let filtered = cities.filter(city => city.city_id == govId);

        filtered.forEach(city => {
            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
        });

        citySelect.disabled = false;

        // في حالة وجود مدينة مختارة مسبقاً
        if (citySelect.getAttribute('data-selected')) {
            citySelect.value = citySelect.getAttribute('data-selected');
        }
    }

    // عند تغيير المحافظة يدويًا
    document.getElementById('governorateSelect').addEventListener('change', loadCities);

    // عند تحميل الصفحة... شغّل نفس الوظيفة تلقائيًا
    window.addEventListener('DOMContentLoaded', loadCities);
</script>

