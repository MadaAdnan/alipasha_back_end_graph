<div class="filter-container">
    <div class="filter-header">
        <i class="fa-solid fa-user-secret"></i>
        <span>تغيير الصورة الشخصية</span>
    </div>
    <form action="{{ route('rechange-password') }}" method="POST">
        @csrf
        <label for="avatar">
            <img style="width: 100px;aspect-ratio: 1;border-radius: 50%;border:1px solid red" src="{{auth()->user()->getImage()}}" alt="Avatar" id="IMG_AVATAR">
        </label>
            <input type="file" class="d-none" name="avatar" id="avatar">

        <button class="btn btn-red-accent">تغيير</button>
    </form>
</div>
