<div class="card card-body my-2 ">
    <form action="">
        <x-form.input-component name="name" :value="old('name')??auth()->user()->name"/>
        <x-form.input-component name="email" :value="old('name')??auth()->user()->email"/>
        <x-form.input-component name="address" :value="old('name')??auth()->user()->address"/>
    </form>
</div>
