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
                        <form action="{{route('confirmedEmail')}}" method="post">
                            @csrf
                            <x-form.input-component name="code" label="كود التفعيل" placeholder="كود التفعيل"/>
                            <button class="btn btn-red-accent">التأكيد</button>
                        </form>
                      <div class="mt-2">
                          <button id="BTN-RESEND" type="button" onclick="resendEmailConfirmation()" class="btn btn-outline-info">إرسال كود التفعيل مرة أخرى</button>
                          <span id="counter"></span>
                      </div>
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
           let btnElement = document.getElementById('BTN-RESEND');



           fetch('{{route('resend-code')}}',{
               method:'POST',
               headers:{
                   'Content-Type':'application/json',
                   'ACCEPT':'application/json',
                   'X-CSRF-TOKEN':'{{csrf_token()}}'
               }
           }) .then(res => res.json())
               .then(data=>{
                   if (data.status=='success') {
                       btnElement.style.display = 'none';
                       counterElement.innerHTML = `<span class="text-danger">60</span> ثانية`;
                       let interval = setInterval(() => {
                           counter--;
                           counterElement.innerHTML = `<span class="text-danger">${counter}</span> ثانية`;
                           if (counter === 0) {
                               clearInterval(interval);
                               counterElement.innerHTML = '';
                               btnElement.style.display = 'inline-block';
                           }
                       }, 1000);
                   }
               })
       }
    </script>
@endpush
