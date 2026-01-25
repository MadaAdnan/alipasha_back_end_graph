<div class="border-bottom  border-1 d-flex justify-content-end gap-2">

    <button class="btn-contact-us" data-bs-target="#exampleModalToggle" data-bs-toggle="modal"><i
            class="fa fa-comments"></i> <span>تواصل معنا</span>
    </button>
    <a class="btn-add-post" href="{{url('/seller')}}"> <i class="fa fa-plus"></i> أضف إعلانك</a>


    {{--    Modal--}}
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
         tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <h2>علي باشا</h2>
                    <p>أهلا بك في علي باشا , هل لديك أي استفسار؟</p>
                    <p>يمكنك التواصل معنا إما من داخل الموقع أو عن طريق واتسآب</p>
                </div>
                <div class="modal-footer">
                    <div class="w-100 d-flex justify-content-center gap-3">
                        <form action="{{route('communities.store')}}" method="post">
                            @csrf
                            @method('post')
                            <button class=" btn-contact-us rounded"><i class="fa fa-comments"></i> <span class="d-none d-md-inline">تواصل عن طريق الموقع</span> </button>
                        </form>
                        <form action="{{route('communities.store')}}" method="post">
                            @csrf
                            @method('post')
                            <button class=" btn-green"><i class="fa-brands fa-whatsapp"></i> <span class="d-none d-md-inline">تواصل عن طريق واتسآب</span> </button>
                        </form>
                        <button type="button" class="btn-red-accent rounded px-2 py-1" data-bs-dismiss="modal" ><i class="fa fa-close"></i> <span class="d-none d-md-inline">إغلاق</span></button>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
