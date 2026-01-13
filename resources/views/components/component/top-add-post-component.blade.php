<div class="border-bottom  border-1 d-flex justify-content-end gap-2">

    <button class="btn-contact-us" data-bs-target="#exampleModalToggle" data-bs-toggle="modal"><i
            class="fa fa-comments"></i> تواصل معنا
    </button>
    <a class="btn-add-post" href=""> <i class="fa fa-plus"></i> أضف إعلانك</a>


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
                            <button class=" btn-contact-us rounded"><i class="fa fa-comments"></i> تواصل عن طريق الموقع </button>
                        </form>
                        <form action="{{route('communities.store')}}" method="post">
                            @csrf
                            @method('post')
                            <button class=" btn-green"><i class="fa-brands fa-whatsapp"></i> تواصل عن طريق واتسآب </button>
                        </form>
                        <button type="button" class="btn-red-accent rounded px-2 py-1" data-bs-dismiss="modal" ><i class="fa fa-close"></i> إغلاق</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Hide this modal and show the first with the button below.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Back to
                        first
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
