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
        @foreach($messages as $message)
            {{$message->body}}
        @endforeach
    </div>
    <div class="">
        <form action="{{route('messages.store')}}" class="chat-input" method="post">
            @csrf
            @method('POST')
            <input type="hidden" name="communityId" value="{{$community->id}}">
            <input type="text" name="body" placeholder="اكتب هنا ...">
            <button class="send-message" type="submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>







</div>
