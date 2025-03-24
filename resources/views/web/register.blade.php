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
    <form id="modalForm">
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
                class="form-control"
                id="descriptionInput"
                placeholder="الاسم"
                required
            ></input>
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
                class="form-control"
                id="descriptionInput"
                placeholder="البريد الإلكتروني"
                required
            ></input>
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
                class="form-control"
                id="descriptionInput"
                placeholder="كلمة"
                required
            ></input>
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
            ></input>
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
                class="form-control"
                id="descriptionInput"
                placeholder="phone"
                type="number"
                required
            ></input>
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
                class="form-select"
                aria-label="Default select example"
                style="text-align: right; font-size: 12px;"
            >
                <option value="1" selected>طرطوس</option>
            </select>
        </div>

        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                المدينة
            </p>
            <select
                name="town"
                class="form-select"
                aria-label="Default select example"
                style="text-align: right; font-size: 12px;"
            >
                <option value="1" selected>كفرسوسة</option>
            </select>
        </div>

        <div class="mb-3">
            <p
                for="descriptionInput"
                class="form-label"
                style="text-align: right; font-size: 12px;"
            >
                العنوان التفصيلي
            </p>
            <textarea
                name="description"
                style="text-align: right; font-size: 12px;"
                class="form-control"
                id="descriptionInput"
                rows="3"
                placeholder="العنوان التفصيلي"
                required
            ></textarea>
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
                type="number"
                required
            ></input>
        </div>

        <button
            type="submit"
            class="btn btn-primary"
            data-bs-dismiss="modal"
        >
            تسجيل الدخول
        </button>
    </form>
    <hr />
    <form action="
      ">
        <button class="google-btn">
            <i class="fab fa-google"></i> تسجيل الدخول عبر Google
        </button>

    </form>
    <hr />
    <a href="{{route('login.ui')}}">
        <button class="google-btn">
            <i class="fab fa-google"></i>     لديك حساب؟ تسجيل الدخول
        </button>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<!-- Content goes here -->
<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</html>
