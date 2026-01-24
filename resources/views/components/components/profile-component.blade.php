<div class="card card-body my-2 ">
    <div class="d-flex justify-content-between">
        <h3 class="fs-4 text-gray">تعديل الملف الشخصي</h3>

    </div>
    <form action="">
        <x-form.input-component name="name" :value="old('name')??auth()->user()->name" label="الاسم" placeholder="الاسم" required/>
        <x-form.input-component name="email" :value="old('name')??auth()->user()->email" label="البريد الإلكتروني" disabled placeholder="البريد الإلكتروني" required/>
        <x-form.input-phone-component placeholder="9XXXXXXXX" :phoneValue="auth()->user()->phone" :countryValue="auth()->user()->country_code" label="رقم الهاتف" id="registerPhone"
                                      name="phone"
                                      countryName="phone_code" required/>
        <x-form.input-component name="address" :value="old('name')??auth()->user()->address" label="العنوان" placeholder="العنوان" required/>
    </form>
</div>
