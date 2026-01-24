<div>
    <div class="filter-header">
        <i class="fas fa-sliders-h"></i>
        <span>تغيير كلمة المرور</span>
    </div>
   <div class="bg-white">
       <x-form.input-password-component name="old_password" required lable="كلمة المرور القديمة" :isRounded="true" id="old_password"/>
       <x-form.input-password-component name="new_password" required lable="كلمة المرور الجديدة" :isRounded="true" id="new_password"/>
       <x-form.input-password-component name="confirm_password" required lable="تأكيد كلمة المرور " :isRounded="true" id="confirm_password"/>
   </div>
</div>
