<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <ul class="nav nav-tabs p-0" id="authTabs" role="tablist">
                <li class="nav-item w-50" role="presentation">
                    <a href="#" class="nav-link active w-100 text-gray"
                            id="login-tab"
{{--                            data-bs-toggle="tab"--}}
                          {{--  data-bs-target="#login"
                            type="button"--}}
{{--                            role="tab"--}}
{{--                            aria-controls="login"--}}
                            aria-selected="true">
                        تسجيل الدخول
                    </a>
                </li>
                <li class="nav-item w-50" role="presentation">
                    <a href="{{route('register.ui')}}" class="nav-link w-100 text-gray"
                            id="register-tab"
                            data-bs-toggle="tab"
                          {{--  data-bs-target="#register"
                            type="button"
                            role="tab"--}}
                            aria-controls="register"
                            aria-selected="false">
                        إشتراك جديد
                    </a>
                </li>
            </ul>

            <div class="card-body tab-content " id="authTabsContent">
                <div class="tab-pane fade show shadow-sm active px-3 py-2 bg-white"
                     id="login"
                     role="tabpanel"
                     aria-labelledby="login-tab">
                    <!-- محتوى تسجيل الدخول -->
                    <form method="post" action="{{route('login')}}">
                        @csrf
                        @method('POST')
                        <x-form.input-component errorKey="email" wrapperClass="mt-1" type="email" class="form-control"
                                                id="loginEmail"
                                                name="email"
                                                value="{{old('email')}}"
                                                label="البريد الإلكتروني" placeholder="example@domain.com" required/>
                        <x-form.input-password-component type="password" name="password" class="form-control" id="loginPassword"
                                                         label="كلمة المرور" required/>
                        <div class="d-flex justify-content-end">
                            <a href="{{route('forget-password.ui')}}" class="my-1">نسيت كلمة المرور؟</a>
                        </div>
                        <button type="submit" class="btn bg-gold w-100">تسجيل الدخول</button>

                    </form>
                    <div class="d-flex justify-content-between align-items-center my-1">
                        <div class="divider flex-grow-1"></div>
                        <span class="flex-grow-1 text-center">أو </span>
                        <div class="divider flex-grow-1"></div>
                    </div>
                    <a href="{{route('google.auth.site')}}" class="btn bg-red w-100 my-1">سجل عن طريق <i
                            class="fa-brands fa-google"></i></a>
                    <div class="d-flex justify-content-between">

                        <a href="{{route('index')}}" class="my-1">عودة للصفحة الرئيسية</a>

                    </div>
                </div>

             {{--   <div class="tab-pane fade shadow-sm active px-3 py-2 bg-white"
                     id="register"
                     role="tabpanel"
                     aria-labelledby="register-tab">
                    <!-- محتوى الإشتراك الجديد -->
                    <form method="post" action="{{route('register')}}">
                        @csrf
                        @method('post')
                        <x-form.input-component wrapperClass="mt-1" type="text" class="form-control" id="registerName"
                                                name="name"
                                                label="الاسم الكامل" placeholder="أدخل اسمك الكامل" required/>

                        <x-form.input-component wrapperClass="mt-1" type="email" class="form-control" id="registerEmail"
                                                name="email"
                                                label="البريد الإلكتروني" placeholder="example@domain.com" required/>
                        <x-form.input-password-component type="password" class="form-control" id="registerPassword" name="password"
                                                         label="كلمة المرور" required name="password"/>
                        <x-form.input-password-component type="password" class="form-control" name="confirmPassword"
                                                         id="registerConfirmPassword" label="تأكيد كلمة المرور"
                                                         required name="confirmPassword"/>

                        <x-form.input-phone-component placeholder="9XXXXXXXX" label="رقم الهاتف" id="registerPhone"
                                                      name="phone"
                                                      countryName="phone_code" required/>

                        <x-form.input-select-component :options="$governorates" key="id" label="اختر محافظتك" id="registerGovernorate"
                                                       name="city" required/>
                        <x-form.input-select-component  label="اختر المدينة" id="registerArea"
                                                       name="area" required/>
                        <x-form.input-component wrapperClass="mt-1" type="text" class="form-control"
                                                id="registerAddress" name="address"
                                                label="العنوان التفصيلي" placeholder="أدخل العنوان التفصيلي" required
                                                name="address"/>
                        <x-form.input-component wrapperClass="mt-1" type="text" class="form-control"
                                                id="registerAddress" name="affiliate"
                                                label="كود الإحالة" placeholder="أدخل كود الإحالة"/>

                        <button type="submit" class="btn bg-gold w-100">إنشاء حساب جديد</button>
                    </form>
                    <div class="d-flex justify-content-between align-items-center my-1">
                        <div class="divider flex-grow-1"></div>
                        <span class="flex-grow-1 text-center">أو </span>
                        <div class="divider flex-grow-1"></div>
                    </div>
                    <a href="{{route('google.auth.site')}}" class="btn bg-red w-100 my-1">سجل عن طريق <i
                            class="fa-brands fa-google"></i></a>
                    <div class="d-flex justify-content-between">

                        <a href="{{route('index')}}" class="my-1">عودة للصفحة الرئيسية</a>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
</div>
<script>
    // تفعيل التبويب حسب الـ hash عند التحميل
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.substring(1);
        if (hash === 'login' || hash === 'register') {
            const tabTrigger = document.querySelector(`[data-bs-target="#${hash}"]`);
            if (tabTrigger) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }
    });
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


