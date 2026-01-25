@props([
    'comments'=>[],
    'post'=>null,
])
<div class=" mt-4">
    <div class="fb-comments card">
        <div class="categories-header">
            <i class="fas fa-comments"></i>
            <span>التعليقات</span>
        </div>

        <div class="card-body comments-box">
@foreach($comments as $comment)
                <!-- تعليق رئيسي -->
                <x-components.comment-item-component :comment="$comment"/>
@endforeach






        </div>
        <div class="card-footer bg-white">
            @auth
                <!-- إضافة تعليق -->
                <div class="add-comment d-flex mt-3 ">
                    <img src="{{auth()->user()->getImage()}}"
                         class="avatar"
                         alt="{{auth()->user()->name}}"
                         onerror="this.src='{{ asset('images/user-profile.png') }}'; this.classList.add('avatar-no-image');">
                    <form action="{{route('comments.store')}}" class="d-flex gap-1 w-100 align-items-center">
                        @csrf
                        <input type="hidden" name="productId" value="{{$post?->id}}">
                        {{--<input type="text"
                               class="form-control flex-grow-1"
                               placeholder="اكتب تعليقًا...">--}}
                        <x-form.input-component name="comment" wrapperClass="flex-grow-1 pt-1" placeholder="اكتب تعليقًا..." required="required"/>
                        <button type="submit" class="btn btn-red-accent rounded-5 px-4 mb-3 ">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>

                </div>
            @endauth
        </div>
    </div>
</div>
<style>
    .fb-comments {
        margin: auto;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .comments-box {
        max-height: 50vh;
        overflow-y: auto;
        padding: 20px;
    }

    .comments-box::-webkit-scrollbar {
        width: 6px;
    }

    .comments-box::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .comments-box::-webkit-scrollbar-thumb {
        background: var(--red);
        border-radius: 3px;
    }

    .comments-box::-webkit-scrollbar-thumb:hover {
        background: var(--red-accent);
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: 10px;
        border: 2px solid #e0e0e0;
    }

    .avatar.small {
        width: 32px;
        height: 32px;
    }

    .avatar.avatar-no-image {
        background: linear-gradient(135deg, #f5f6fa 0%, #e9ecef 100%);
        object-fit: contain;
        padding: 3px;
    }

    .comment {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .comment:last-child {
        border-bottom: none;
    }

    .comment-body {
        width: 100%;
    }

    .comment-box {
        background: #f0f2f5;
        padding: 12px 16px;
        border-radius: 12px;
        display: inline-block;
        max-width: 100%;
        transition: all 0.3s ease;
    }

    .comment-box:hover {
        background: #e4e6eb;
    }

    .comment-box p {
        margin: 0;
        color: var(--gray-text);
        line-height: 1.5;
    }

    .comment-actions {
        font-size: 12px;
        color: #65676b;
        margin-right: 10px;
        margin-top: 8px;
        display: flex;
        gap: 15px;
    }

    .comment-actions a {
        text-decoration: none;
        color: #65676b;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .comment-actions a:hover {
        color: var(--red);
    }

    .replies {
        margin-top: 15px;
        padding-right: 45px;
        border-right: 2px solid #e0e0e0;
    }

    .add-comment {
        padding: 20px;
        border-top: 1px solid #f0f0f0;
        background: #f8f9fa;
    }

    .add-comment input {
        border-radius: 20px;
        background: white;
        border: 1px solid #e0e0e0;
        padding: 10px 16px;
        transition: all 0.3s ease;
    }

    .add-comment input:focus {
        box-shadow: 0 0 0 3px rgba(227, 6, 19, 0.1);
        border-color: var(--red);
        background: white;
    }

    .add-comment button {
        border-radius: 20px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .add-comment button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(227, 6, 19, 0.3);
    }
</style>
