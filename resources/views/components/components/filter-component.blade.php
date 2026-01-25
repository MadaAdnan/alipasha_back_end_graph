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
        <div class="filter-group position-relative">
            <label class="filter-label" for="Search">بحث</label>
            <input class="filter-input" id="Search" name="q" placeholder="ابحث عن... "/>
            <div id="suggestions" class="position-absolute bg-white w-100">
                <ul id="suggestionsUl">

                </ul>
            </div>
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
    function getSuggestions() {
        let q = document.getElementById('Search').value;
        console.log(q);
        if (!q) {
            document.getElementById('suggestions').style.display = 'none';
            return;
        }
        fetch(`/api/suggestions?q=${q}`)
            .then(res => res.json())
            .then(data => {
                let suggestions = document.getElementById('suggestionsUl');
                suggestions.style.display = 'block';
                suggestions.innerHTML = '';
                data.forEach(suggestion => {
                    suggestions.innerHTML += `<li class="sug" data-text="${suggestion}">${suggestion}</li>`;
                });
            })
    }
    function setSearch(text) {
        let search = document.getElementById('Search');
        search.value = text;

        // إخفاء الاقتراحات بعد الاختيار
        document.getElementById('suggestions').style.display = 'none';
    }
    function debounce(fn, delay = 300) {
        let timeout;

        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fn.apply(this, args);
            }, delay);
        };
    }
    const debouncedGetSuggestions = debounce(getSuggestions, 300);
    document.getElementById('Search').addEventListener('input', debouncedGetSuggestions);
    document.getElementById('suggestionsUl').addEventListener('click', function (e) {
        if (e.target.classList.contains('sug')) {
            setSearch(e.target.dataset.text);
        }
    });
    // عند تغيير المحافظة يدويًا
    document.getElementById('governorateSelect').addEventListener('change', loadCities);

    // عند تحميل الصفحة... شغّل نفس الوظيفة تلقائيًا
    window.addEventListener('DOMContentLoaded', loadCities);


</script>

