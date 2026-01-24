<div class="card card-body my-2 ">
    <div class="d-flex justify-content-between">
        <h3 class="fs-4 text-gray">تعديل الملف الشخصي</h3>

    </div>
    <form action="">
        <x-form.input-component name="name" :value="old('name')??auth()->user()->name" label="الاسم" placeholder="الاسم" required/>
        <x-form.input-component name="email" :value="old('name')??auth()->user()->email" label="البريد الإلكتروني" disabled placeholder="البريد الإلكتروني" required/>
        <x-form.input-phone-component placeholder="9XXXXXXXX" :value="auth()->user()->phone" :countryValue="auth()->user()->phone_code" label="رقم الهاتف" id="registerPhone"
                                      name="phone"
                                      countryName="phone_code" required/>
        <x-form.input-select-component :options="$governorates" :value="auth()->user()->city_id" key="id" label="اختر محافظتك" id="registerGovernorate"
                                       name="city" required/>
        <x-form.input-select-component  label="اختر المدينة" id="registerArea"
                                        name="area" required :value="auth()->user()->area_id"/>
        <x-form.input-component name="address" :value="old('name')??auth()->user()->address" label="العنوان" placeholder="العنوان" required/>
    </form>
</div>
<script>
    const cities = @json($cities);

    function loadCities() {

        let govSelect = document.getElementById('registerGovernorate');
        let govId = govSelect.value;
        let citySelect = document.getElementById('registerArea');

        citySelect.innerHTML = '<option value="">اختر المدينة</option>';

        if (!govId) {
            citySelect.disabled = true;
            return;
        }

        let filtered = cities.filter(city => city.city_id == govId);

        filtered.forEach(city => {
            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
        });
        console.log(filtered)
        citySelect.disabled = false;

        // في حالة وجود مدينة مختارة مسبقاً
        if (citySelect.getAttribute('data-selected')) {
            citySelect.value = citySelect.getAttribute('data-selected');
        }
    }

    // عند تغيير المحافظة يدويًا
    document.getElementById('registerGovernorate').addEventListener('change', loadCities);

    // عند تحميل الصفحة... شغّل نفس الوظيفة تلقائيًا
    window.addEventListener('DOMContentLoaded', loadCities);
</script>
