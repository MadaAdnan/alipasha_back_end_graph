<div class="filter-container">
    <div class="filter-header">
        <i class="fa-regular fa-image"></i>
        <span>تغيير الصورة الشخصية</span>
    </div>
    <form action="{{ route('change-avatar') }}" enctype="multipart/form-data" method="POST" class="d-flex flex-column justify-content-center align-items-center gap-2">
        @csrf
        <label for="avatar" class="position-relative">

           <span class="d-flex justify-content-center align-items-center w-25 text-center border border-danger bg-white position-absolute text-muted rounded p-1" style="z-index: 10;top:5px;right:-5px" >
                <i class="fa-regular fa-image fs-5" ></i>
           </span>
            <img style="width: 100px;aspect-ratio: 1;border-radius: 50%;border:1px solid red" src="{{auth()->user()->getImage()}}" alt="Avatar" id="IMG_AVATAR">
        </label>
            <input type="file" accept="image/png,image/jpeg,image/webp" class="d-none" name="avatar" id="avatar">
@error('avatar') <span class="text-danger">{{$message}}</span> @enderror

        <button class="btn btn-red-accent">حفظ</button>
    </form>
</div>
<script>
    document.getElementById('avatar').addEventListener('change',function (e) {
        let file = e.target.files[0];
        let reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('IMG_AVATAR').src = e.target.result;
        }
        reader.readAsDataURL(file);
    })
</script>
