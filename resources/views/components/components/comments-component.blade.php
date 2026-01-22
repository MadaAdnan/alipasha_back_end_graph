@props([
    'comments'=>[],
    'post'=>null,
])
<div class="container-fluid mt-4">
    <div class="fb-comments card">
        <div class="card-body">
@foreach($comments as $comment)
                <x-components.comment-item-component :comment="$comment"/>
@endforeach
            <!-- تعليق رئيسي -->

@auth
        <!-- إضافة تعليق -->
        <div class="add-comment d-flex mt-3">
            <img src="{{auth()->user()->getImage()}}" class="avatar">
            <form action="{{route('comments.store')}}" class="d-flex gap-1 w-100 align-items-center">
                @csrf
                <input type="hidden" name="productId" value="{{$comment->product?->id}}">
                {{--<input type="text"
                       class="form-control flex-grow-1"
                       placeholder="اكتب تعليقًا...">--}}
                <x-form.input-component name="comment" wrapperClass="flex-grow-1 pt-1" placeholder="اكتب تعليقًا..." required/>
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
        /*max-width: 700px;*/
        margin: auto;
        border-radius: 10px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-left: 10px;
    }

    .avatar.small {
        width: 32px;
        height: 32px;
    }

    .comment {
        margin-bottom: 15px;
    }

    .comment-body {
        width: 100%;
    }

    .comment-box {
        background: #f0f2f5;
        padding: 8px 12px;
        border-radius: 15px;
        display: inline-block;
        max-width: 100%;
    }

    .comment-box p {
        margin: 2px 0 0;
    }

    .comment-actions {
        font-size: 13px;
        color: #65676b;
        margin-right: 10px;
    }

    .comment-actions a {
        text-decoration: none;
        color: #65676b;
        font-weight: 500;
    }

    .comment-actions a:hover {
        text-decoration: underline;
    }

    .replies {
        margin-top: 10px;
        padding-right: 45px;
    }

    .add-comment input {
        border-radius: 20px;
        background: #f0f2f5;
        border: none;
    }

    .add-comment input:focus {
        box-shadow: none;
    }

</style>
