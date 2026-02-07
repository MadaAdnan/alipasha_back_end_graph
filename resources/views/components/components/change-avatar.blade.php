<div class="filter-container">
    <div class="filter-header">
        <i class="fa-solid fa-user-secret"></i>
        <span>تغيير الصورة الشخصية</span>
    </div>
    <form action="{{ route('rechange-password') }}" method="POST" class="d-flex flex-column justify-content-center align-items-center gap-2">
        @csrf
        <label for="avatar" class="position-relative">

           <span class="inline-block w-25 bg-white position-absolute text-muted rounded-circle py-1 px-2" style="z-index: 10;top:45%;left:40%" >
                <i class="fa-regular fa-image " ></i>
           </span>
            <img style="width: 100px;aspect-ratio: 1;border-radius: 50%;border:1px solid red" src="{{auth()->user()->getImage()}}" alt="Avatar" id="IMG_AVATAR">
        </label>
            <input type="file" class="d-none" name="avatar" id="avatar">

        <button class="btn btn-red-accent">حفظ</button>
    </form>
</div>
