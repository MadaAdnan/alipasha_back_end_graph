@extends('theme2.layouts.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                @php
                    $wallet=\App\Models\Setting::first()?->wallet;
                @endphp

                <!-- رأس الصفحة -->
                <div class="filter-container">
                    <div class="filter-header">
                        <i class="fas fa-wallet"></i>
                        <span>طريقة شحن الحساب عبر شام كاش</span>
                    </div>
                </div>

                <!-- قسم المعلومات المهمة -->
                <div class="payment-info-section">
                    <div class="payment-info-card">
                        <div class="payment-info-header">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>معلومات مهمة</span>
                        </div>
                        <div class="payment-info-content">
                            <div class="info-item">
                                <span class="info-label">عنوان محفظة علي باشا (أرسل الأموال إلى):</span>
                                <div class="info-value-box">
                                    <span class="info-value">{{$wallet}}</span>
                                    <button class="copy-btn" onclick="copyTextToClipboard('{{$wallet}}')">
                                        <i class="fa fa-copy"></i>
                                        <span>انسخ</span>
                                    </button>
                                </div>
                                <p class="info-hint">هذا هو عنوان محفظة منصة علي باشا في تطبيق شام كاش. أرسل الأموال إلى هذا العنوان</p>
                            </div>

                            <div class="info-item">
                                <span class="info-label">رقم حسابك في علي باشا (ضعه في الملاحظات):</span>
                                <div class="info-value-box">
                                    <span class="info-value">{{auth()->id()}}</span>
                                    <button class="copy-btn" onclick="copyTextToClipboard('{{auth()->id()}}')">
                                        <i class="fa fa-copy"></i>
                                        <span>انسخ</span>
                                    </button>
                                </div>
                                <p class="info-hint">ضع هذا الرقم في حقل الملاحظات عند التحويل حتى نتمكن من تحديد حسابك وشحنه</p>
                            </div>

                            <div class="warning-box">
                                <i class="fas fa-info-circle"></i>
                                <span>قم بالتحويل بالدولار الأمريكي. في حال التحويل بعملة أخرى، سيتم احتساب قيمتها بالدولار</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- قسم الخطوات -->
                <div class="payment-steps-section">
                    <div class="filter-container">
                        <div class="filter-header">
                            <i class="fas fa-list-ol"></i>
                            <span>خطوات الشحن</span>
                        </div>

                        <div class="steps-container">
                            <!-- الخطوة الأولى -->
                            <div class="step-card">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h5 class="step-title">افتح تطبيق شام كاش</h5>
                                    <p class="step-description">قم بفتح تطبيق شام كاش على هاتفك وابدأ عملية التحويل</p>
                                    <img class="step-image" src="{{asset('images/payment/payment1.jpg')}}" alt="الخطوة الأولى">
                                </div>
                            </div>

                            <!-- الخطوة الثانية -->
                            <div class="step-card">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h5 class="step-title">أرسل الأموال إلى محفظة علي باشا</h5>
                                    <p class="step-description">أدخل عنوان محفظة علي باشا المذكور أعلاه في حقل المستقبل</p>
                                    <img class="step-image" src="{{asset('images/payment/payment2.jpg')}}" alt="الخطوة الثانية">
                                </div>
                            </div>

                            <!-- الخطوة الثالثة -->
                            <div class="step-card">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h5 class="step-title">أضف رقم حسابك في الملاحظات</h5>
                                    <p class="step-description">ضع رقم حسابك في علي باشا في حقل الملاحظات حتى نتمكن من تحديد حسابك وشحنه</p>
                                    <img class="step-image" src="{{asset('images/payment/payment3.jpg')}}" alt="الخطوة الثالثة">
                                </div>
                            </div>
                        </div>

                        <div class="note-box">
                            <i class="fas fa-clock"></i>
                            <span>قد يستغرق شحن الرصيد من 5 إلى 30 دقيقة</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        function copyTextToClipboard(text) {
            navigator.clipboard.writeText(text);
            showToast('تم نسخ النص بنجاح');
        }
    </script>

@endpush
