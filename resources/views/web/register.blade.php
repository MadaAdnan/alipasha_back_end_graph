<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>علي باشا - تسجيل حساب جديد</title>
    <link rel="icon" type="image/png" href="{{asset('assets/logo.svg')}}"/>
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
        rel="stylesheet"
    />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/shared.css')}}"/>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
            padding: 40px 0px;
        }

        .login-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: right;
        }

        .google-btn {
            background: #db4437;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
        }

        .google-btn:hover {
            background: #c1351d;
        }

        .google-btn i {
            font-size: 20px;
        }
    </style>
</head>
<body>
<div class="login-box">
    <h3>انشاء حساب</h3>
    <form id="modalForm" method="post" action="{{route('register')}}">
        @csrf
        @method('post')
        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                الاسم
            </p>
            <input
                name="name"
                style="text-align: right; font-size: 12px;"
                class="form-control  @error('name')
                    is-invalid
@enderror"
                id="descriptionInput"
                placeholder="الاسم"
                required
                value="{{old('name')}}"
            />
            @error('name')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>


        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                البريد الإلكتروني
            </p>
            <input
                name="email"
                style="text-align: right; font-size: 12px;"
                class="form-control
@error('email')
                    is-invalid
@enderror"
                id="descriptionInput"
                placeholder="البريد الإلكتروني"
                required
                value="{{old('email')}}"
            />
            @error('email')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                كلمة المرور
            </p>
            <input
                type="password"
                name="password"
                style="text-align: right; font-size: 12px;"
                class="form-control
@error('password')
                    is-invalid
@enderror"
                id="descriptionInput"
                placeholder="كلمة"
                required
            />
            @error('password')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                تاكيد كلمة المرور
            </p>
            <input
                type="password"
                name="confiermPassword"
                style="text-align: right; font-size: 12px;"
                class="form-control"
                id="descriptionInput"
                placeholder="تاكيد كلمة المرور"
                required
            />
        </div>

        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                رقم الهاتف
            </p>
            <input
                name="phone"
                style="text-align: right; font-size: 12px;"
                class="form-control
@error('phone')
                    is-invalid
@enderror"
                id="descriptionInput"
                placeholder="phone"
                type="text"
                required
                value="{{old('phone')}}"
            />
            @error('phone')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>


        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                المحافظة
            </p>
            <select
                name="city"
                class="form-select @error('city')
                    is-invalid
@enderror"
                aria-label="Default select example"
                style="text-align: right; font-size: 12px;"
            >
                <option value="" >حدد مدينتك</option>
                @foreach($cities as $city)
                    <option @if(old('city')==$city->id) selected @endif value="{{$city->id}}">{{$city->name}}</option>
                @endforeach
            </select>
            @error('city')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>


        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                العنوان التفصيلي
            </p>
            <input
                type="text"
                name="address"
                style="text-align: right; font-size: 12px;"
                class="form-control @error('address')
                    is-invalid
@enderror

                    "
                id="descriptionInput"
                value="{{old('address')}}"
                placeholder="العنوان التفصيلي"
                required
            />
            @error('address')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                كود الإحالة
            </p>
            <input
                name="code"
                style="text-align: right; font-size: 12px;"
                class="form-control"
                id="descriptionInput"
                placeholder=" كود الإحالة"
                type="text"

            />

        </div>

        <button
            type="submit"
            class="btn btn-primary"
            data-bs-dismiss="modal"
        >
            تسجيل الدخول
        </button>
    </form>
    <hr/>
    <form action="
      ">
        <button class="google-btn">
            <i class="fab fa-google"></i> تسجيل الدخول عبر Google
        </button>

    </form>
    <hr/>
    <a href="{{route('login.ui')}}">
        <button class="google-btn">
            <i class="fab fa-google"></i> لديك حساب؟ تسجيل الدخول
        </button>
    </a>
    <div class="d-flex my-2 justify-content-end">
        <a href="{{route('index')}}">
            <i class="bi bi-arrow-return-left"></i>
            عودة للصفحة الرئيسية
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<!-- Content goes here -->
<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</html>
