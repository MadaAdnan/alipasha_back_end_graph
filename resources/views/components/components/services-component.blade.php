@props([
    'services'=>[]
])
<div class="container mt-4">

    <!-- عنصر -->
    <div class="service-card d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-box">
                <i class="fa-solid fa-briefcase"></i>
            </div>

            <div>
                <h6 class="mb-1 fw-bold">خدمات مالية وإدارية</h6>
                <p class="mb-1 text-muted small">
                    خدمات مالية وإدارية
                </p>
                <span class="badge bg-light text-dark">
                    <i class="fa-solid fa-location-dot"></i> إعزاز
                </span>
            </div>
        </div>

        <a href="#" class="btn btn-success btn-sm">
            استعراض <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    <!-- عنصر -->
    <div class="service-card d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-box bg-primary">
                <i class="fa-solid fa-building"></i>
            </div>

            <div>
                <h6 class="mb-1 fw-bold">أحمد الدقاق</h6>
                <p class="mb-1 text-muted small">
                    برج التجارة الطابق الأرضي مكتب رقم IV
                </p>
                <span class="badge bg-light text-dark">
                    <i class="fa-solid fa-location-dot"></i> سرمدا
                </span>
            </div>
        </div>

        <a href="#" class="btn btn-success btn-sm">
            استعراض <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    <!-- عنصر -->
    <div class="service-card d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-box bg-warning">
                <i class="fa-solid fa-clock"></i>
            </div>

            <div>
                <h6 class="mb-1 fw-bold">خدمة 24 ساعة</h6>
                <p class="mb-1 text-muted small">
                    استقبال وإرسال من وإلى كافة أنحاء العالم
                </p>
                <span class="badge bg-light text-dark">
                    <i class="fa-solid fa-location-dot"></i> عفرين
                </span>
            </div>
        </div>

        <a href="#" class="btn btn-success btn-sm">
            استعراض <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

</div>
<style>


    .service-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,.05);
        transition: 0.2s ease;
    }

    .service-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,.08);
    }

    .icon-box {
        width: 45px;
        height: 45px;
        background: #198754;
        color: #fff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .btn-success {
        border-radius: 20px;
        padding: 6px 14px;
    }

</style>
