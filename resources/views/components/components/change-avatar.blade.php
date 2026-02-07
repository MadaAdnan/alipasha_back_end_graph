<div class="filter-container">
    <div class="filter-header">
        <i class="fa-solid fa-user-secret"></i>
        <span>تغيير الصورة الشخصية</span>
    </div>
    <form action="{{ route('rechange-password') }}" method="POST" class="d-flex flex-column justify-content-center align-items-center gap-2">
        @csrf
        <label for="avatar" class="position-relative">

           <span class="d-flex justify-content-center align-items-center w-25 text-center border border-warning bg-white position-absolute text-muted rounded p-1" style="z-index: 10;top:5px;right:-5px" >
                <i class="fa-regular fa-image fs-5" ></i>
           </span>
            <img style="width: 100px;aspect-ratio: 1;border-radius: 50%;border:1px solid red" src="{{auth()->user()->getImage()}}" alt="Avatar" id="IMG_AVATAR">
        </label>
            <input type="file" class="d-none" name="avatar" id="avatar">

        <button class="btn btn-red-accent">حفظ</button>
    </form>
</div>
