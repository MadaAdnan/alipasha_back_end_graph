<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <ul class="nav nav-tabs p-0" id="authTabs" role="tablist">
                <li class="nav-item w-100" role="presentation">
                    <button class="nav-link active w-100 text-gray"
                            id="login-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#login"
                            type="button"
                            role="tab"
                            aria-controls="login"
                            aria-selected="true">
                        طلب تغير كلمة المرور
                    </button>
                </li>

            </ul>

            <div class="card-body tab-content " id="authTabsContent">
                <div class="tab-pane fade show shadow-sm active px-3 py-2 bg-white"
                     id="login"
                     role="tabpanel"
                     aria-labelledby="login-tab">

                    <form action="{{route('forget-password')}}" method="post">
                        @csrf
                        @method('post')
                        <x-form.input-component errorKey="email" wrapperClass="mt-1" type="email" class="form-control"
                                                id="loginEmail"
                                                name="email"
                                                value="{{old('email')}}"
                                                label="البريد الإلكتروني" placeholder="example@domain.com" required/>

                        <button type="submit" class="btn bg-gold w-100">طلب تغير كلمة المرور</button>

                    </form>

                    <div class="d-flex justify-content-between">

                        <a href="{{route('index')}}" class="my-1">عودة للصفحة الرئيسية</a>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

