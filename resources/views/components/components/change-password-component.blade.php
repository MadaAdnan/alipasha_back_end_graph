<div class="filter-container">
    <div class="filter-header">
        <i class="fas fa-sliders-h"></i>
        <span>تغيير كلمة المرور</span>
    </div>
    <form action="{{ route('change-password') }}" method="POST">
        <x-form.input-password-component name="old_password" required label="كلمة المرور القديمة"
                                         placeholder="كلمة المرور القديمة" :isRounded="true" id="old_password"/>
        <x-form.input-password-component name="new_password" required label="كلمة المرور الجديدة"
                                         placeholder="كلمة المرور الجديدة" :isRounded="true" id="new_password"/>
        <x-form.input-password-component name="confirm_password" required label="تأكيد كلمة المرور "
                                         placeholder="تأكيد كلمة المرور " :isRounded="true" id="confirm_password"/>
        <button class="btn btn-red-accent">تغيير</button>
    </form>
</div>
