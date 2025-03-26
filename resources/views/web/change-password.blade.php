<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>تغيير كلمة المرور</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <script
        src="https://kit.fontawesome.com/a076d05399.js"
        crossorigin="anonymous"
    ></script>
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
    <h3>نسيت كلمة المرور</h3>
    <form action="{{route('change-password')}}" method="post">
        @csrf
        @method('post')
        <input type="hidden" value="{{request()->get('code')}}" name="code">
        @error('code')
        <span class="text-danger my-2 d-inline-block">{{$message}}</span>
        @enderror
        <div class="mb-3">
            <label for="password" class="form-label">كلمة السر</label>
            <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                aria-describedby="passwordHelp"
            />
            @error('password')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="confiemPassword" class="form-label"
            >تاكيد كلمة السر</label
            >
            <input
                type="confiemPassword"
                class="form-control"
                id="confiemPassword"
                name="confiemPassword"
                aria-describedby="confiemPasswordHelp"
            />

        </div>
        <button type="submit" class="btn btn-primary w-100">تاكيد الكود</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
