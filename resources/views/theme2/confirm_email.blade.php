@extends('theme2.layouts.master')


@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="filter-container">
                    <div class="filter-header">
                        <i class="fas fa-sliders-h"></i>
                        <span>تأكيد البريد الإلكتروني</span>
                    </div>
                    <div>
                        <p class="lead">تم إرسال كود التفعيل إلى بريدك الإلكتروني يرجى التأكد منه</p>
                        <form action="">
                            <x-form.input-component name="code" label="كود التفعيل" placeholder="كود التفعيل"/>
                            <button class="btn btn-red-accent">التأكيد</button>
                        </form>
                        <button type="button" onclick="resendEmailConfirmation()" class="btn btn-outline-info">إرسال كود التفعيل مرة أخرى</button>
                        <span id="counter"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        let counter = 60;
       function resendEmailConfirmation() {
           let counterElement = document.getElementById('counter');
           counterElement.innerHTML = `<span class="text-danger">60</span> ثانية`;
           let interval = setInterval(() => {
               counter--;
               counterElement.innerHTML = `<span class="text-danger">${counter}</span> ثانية`;
               if (counter === 0) {
                   clearInterval(interval);
                   counterElement.innerHTML = '';
               }
           }, 1000);
       }
    </script>
@endpush
