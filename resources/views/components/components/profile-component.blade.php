<div class="card card-body my-2 ">
    <div class="d-flex justify-content-between">
        <h3 class="fs-4 text-gray">تعديل الملف الشخصي</h3>

    </div>
    <form action="">
        <x-form.input-component name="name" :value="old('name')??auth()->user()->name"/>
        <x-form.input-component name="email" :value="old('name')??auth()->user()->email"/>
        <x-form.input-component name="address" :value="old('name')??auth()->user()->address"/>
    </form>
</div>
