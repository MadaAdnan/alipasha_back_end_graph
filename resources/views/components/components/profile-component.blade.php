<div class="card card-body my-2 ">
    <div class="d-flex justify-content-between">
        <h3 class="fs-4 text-gray">تعديل الملف الشخصي</h3>

    </div>
    <form action="{{route('profile.store')}}" method="POST">
        @csrf
        <x-form.input-component name="name" :value="old('name')??auth()->user()->name" label="الاسم" placeholder="الاسم"
                                :disabled="auth()->user()->is_verified"
                                :helpText="auth()->user()->is_verified?'حسابك موثق لا يمكن تغيير الاسم':''" required/>
        <x-form.input-component name="seller_name" :disabled="!auth()->user()->is_verified"
                                :value="old('seller_name')??auth()->user()->seller_name" label="اسم المتجر"
                                placeholder="اسم المتجر" required/>
        <x-form.input-component name="email" :value="old('email')??auth()->user()->email" label="البريد الإلكتروني"
                                disabled placeholder="البريد الإلكتروني" required/>
        <x-form.input-phone-component placeholder="9XXXXXXXX" :value="auth()->user()->phone"
                                      :countryValue="auth()->user()->phone_code" label="رقم الهاتف" id="registerPhone"
                                      name="phone"
                                      countryName="phone_code" required/>
        <x-form.input-select-component :options="$governorates" :value="auth()->user()->city_id" key="id"
                                       label="اختر محافظتك" id="registerGovernorate"
                                       name="city_id" required/>
        <x-form.input-select-component label="اختر المدينة" id="registerArea"
                                       name="area_id" required/>
        <x-form.input-component name="address" :value="old('name')??auth()->user()->address" label="العنوان"
                                placeholder="العنوان" required/>
        <x-form.input-component :goto="!auth()->user()->is_social ?route('plans.index'):''" helpText="يجب ترقية الحساب"
                                name="face" :value="old('face')??auth()->user()->social['face']" label="رابط فيس بوك"
                                :disabled="!auth()->user()->is_social" placeholder="رابط فيس بوك" type="url"/>
        <x-form.input-component :goto="!auth()->user()->is_social ? route('plans.index') :''"
                                :helpText="!auth()->user()->is_social ?'يجب ترقية الحساب':''" name="instagram"
                                :value="old('instagram')??auth()->user()->social['instagram']" label="رابط إنستغرام"
                                :disabled="!auth()->user()->is_social" placeholder="رابط إنستغرام" type="url"/>
        <x-form.input-component :goto="!auth()->user()->is_social ?route('plans.index'):''" :helpText="!auth()->user()->is_social ?'يجب ترقية الحساب':''"
                                name="tiktok" :value="old('tiktok')??auth()->user()->social['tiktok']"
                                label="رابط تيك توك" :disabled="!auth()->user()->is_social" placeholder="رابط تيك توك"
                                type="url"/>
        <x-form.input-component :goto="!auth()->user()->is_social ?route('plans.index'):''" :helpText="!auth()->user()->is_social ?'يجب ترقية الحساب':''"
                                name="twitter" :value="old('twitter')??auth()->user()->social['twitter']"
                                label="رابط تويتر" :disabled="!auth()->user()->is_social" placeholder="رابط تويتر"
                                type="url"/>
        <x-form.input-component :goto="!auth()->user()->is_social ?route('plans.index'):''" :helpText="!auth()->user()->is_social ?'يجب ترقية الحساب':''"
                                name="linkedin" :value="old('linkedin')??auth()->user()->social['linkedin']"
                                label="رابط لينكد ان" :disabled="!auth()->user()->is_social" placeholder="رابط لينكد ان"
                                type="url"/>

        <button class="btn btn-red-accent">حفظ</button>
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
        const current_area = "{{old('area_id')??auth()->user()->area_id}}"
        filtered.forEach(city => {
            citySelect.innerHTML += `<option ${current_area==city.id?'selected':''} value="${city.id}">${city.name}</option>`;
        });

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
