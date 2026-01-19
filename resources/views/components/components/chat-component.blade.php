@props([
    'community'=>null
])
<div>

    <div class="categories-header">
        @if($community)
            @switch($community->type)
                @case(\App\Enums\CommunityTypeEnum::CHAT->value)
                <i class="fa-solid fa-comments"></i>
                @break
                @case(\App\Enums\CommunityTypeEnum::GROUP->value)
                <i class="fa-solid fa-users-between-lines"></i>
                @break
                @case(\App\Enums\CommunityTypeEnum::CHANNEL->value)
                <i class="fa-solid fa-bullhorn"></i>
                @break

            @endswitch
        <span>{{$community->name}}</span>
        @else
            <i class="fas fa-list"></i>
            <span>لا يوجد محادثات لعرضها</span>
        @endif
    </div>
    <div class="chat-container">
    </div>
    <div class="">
        <form action="" lass="chat-input">
            <input type="text" placeholder="اكتب هنا ...">
            <button class="send-message">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>







</div>
