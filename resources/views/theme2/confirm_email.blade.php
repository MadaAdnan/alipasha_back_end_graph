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
                            <button class="btn btn-red">التأكيد</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
