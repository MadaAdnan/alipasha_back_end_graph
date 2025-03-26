<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ALI BASHA</title>
    <link rel="icon" type="image/png" href="{{asset('assets/logo.svg')}}" />
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
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/shared.css')}}" />
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
    <h3>تسجيل الدخول</h3>
    <form method="post" action="{{route('login')}}">
        @csrf
        @method('post')
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                aria-describedby="emailHelp"
            />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" name="password" id="password" />
        </div>
        @if(session()->has('error'))
        <div class="mb-3 text-danger">{{session()->get('error')}}</div>
        @endif
        <a
            href="{{route('forget-password.ui')}}"
            style="
            font-size: 14px;
            text-decoration: underline;
            margin-bottom: 8px;
          "
        >
            نسيت كلمة المرور
        </a>
        <!-- <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="check" />
          <label class="form-check-label" for="check">تذكرني</label>
        </div> -->
        <button type="submit" class="btn btn-primary w-100">
            تسجيل الدخول
        </button>
    </form>
    <hr />
    <form
        action="
      "
    >
        <button class="google-btn">
            <i class="fab fa-google"></i> تسجيل الدخول عبر Google
        </button>
    </form>

    <hr />
    <a href="{{route('register.ui')}}">
        <button class="google-btn">
            <i class="fab fa-google"></i> ليس لديك حساب؟ أنشئ حساب جديد
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
