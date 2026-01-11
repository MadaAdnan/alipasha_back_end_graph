@props([
    'route'=>null
])
<form class="bg-white p-2 mt-2 rounded" action="{{$route??route('search.index')}}" method="get">
    <div class="row  ">
        {{-- المحافظة --}}
        <div class="col-md-12">
            <label class="form-label">المحافظة</label>
            <select class="form-select" id="governorateSelect" name="city_id">
                <option value="">اختر المحافظة</option>
                @foreach($governorates as $gov)
                    <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- المدينة --}}
        <div class="col-md-12">
            <label class="form-label">المدينة</label>
            <select class="form-select" id="citySelect" disabled name="area_id">
                <option value="">اختر المدينة</option>
            </select>
        </div>

        {{-- السعر من --}}
        <div class="col-md-6 ">
            <label class="form-label">السعر من</label>
            <input type="number" class="form-control" id="priceFrom" placeholder="0" name="price_from">
        </div>

        {{-- السعر إلى --}}
        <div class="col-md-6">
            <label class="form-label">السعر إلى</label>
            <input type="number" class="form-control" id="priceTo" placeholder="0" name="price_to">
        </div>

    </div>
    <button class="btn bg-red-accent w-100 my-1">إرسال</button>
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

