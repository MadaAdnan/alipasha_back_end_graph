<div>
    <div class="filter-header">
        <i class="fas fa-sliders-h"></i>
        <span>تغيير كلمة المرور</span>
    </div>
    <x-form.input-password-component name="old_password" required lable="كلمة المرور القديمة" :isRounded="true"/>
    <x-form.input-password-component name="new_password" required lable="كلمة المرور الجديدة" :isRounded="true"/>
    <x-form.input-password-component name="confirm_password" required lable="تأكيد كلمة المرور " :isRounded="true"/>
</div>
