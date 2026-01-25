@props([
    'community'=>null
])
<div class="chat-wrapper">

    <div class="chat-header">
        @if($community)
            <div class="chat-header-content">
                <div class="chat-header-left">
                    @switch($community->type)
                        @case(\App\Enums\CommunityTypeEnum::CHAT->value)
                        <i class="fa-solid fa-comments chat-header-icon"></i>
                        @break
                        @case(\App\Enums\CommunityTypeEnum::GROUP->value)
                        <i class="fa-solid fa-users-between-lines chat-header-icon"></i>
                        @break
                        @case(\App\Enums\CommunityTypeEnum::CHANNEL->value)
                        <i class="fa-solid fa-bullhorn chat-header-icon"></i>
                        @break
                    @endswitch
                    <div class="chat-header-info">
                        <h3 class="chat-header-title">{{$community->name}}</h3>
                        <span class="chat-header-type">
                            @switch($community->type)
                                @case(\App\Enums\CommunityTypeEnum::CHAT->value)
                                محادثة خاصة
                                @break
                                @case(\App\Enums\CommunityTypeEnum::GROUP->value)
                                مجموعة ({{ $community->users_count ?? 0 }} أعضاء)
                                @break
                                @case(\App\Enums\CommunityTypeEnum::CHANNEL->value)
                                قناة ({{ $community->users_count ?? 0 }} متابع)
                                @break
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="chat-header-actions">
                    <button class="chat-header-btn" title="معلومات">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                    <button class="chat-header-btn" title="البحث">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        @else
            <div class="chat-header-empty">
                <i class="fas fa-list"></i>
                <span>لا يوجد محادثات لعرضها</span>
            </div>
        @endif
    </div>
    <div class="chat-messages-container">
        @if(count($messages) > 0)
            @foreach($messages as $message)
                <x-components.chat-message-component :message="$message"/>
            @endforeach
        @else
            <div class="chat-empty-state">
                <i class="fa-solid fa-comments"></i>
                <p>لا توجد رسائل بعد. ابدأ المحادثة الآن!</p>
            </div>
        @endif
    </div>
    <div class="chat-input-wrapper">
        <form action="{{route('messages.store')}}" class="chat-input-form" method="post" id="chat-form">
            @csrf
            @method('POST')
            <input type="hidden" name="communityId" value="{{$community->id}}">
            <div class="chat-input-container">
                <div class="chat-input-field-wrapper">
                    <input type="text" name="body" class="chat-input-field" placeholder="اكتب رسالتك هنا..." required>
                    <span class="chat-input-focus-border"></span>
                </div>
                <button class="chat-send-btn" type="submit" title="إرسال الرسالة">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            const input = this.querySelector('.chat-input-field');
            if (!input.value.trim()) {
                e.preventDefault();
            }
        });
    </script>







</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // اختيار حاوية المحادثة
        const chatContainer = document.querySelector('.chat-container');

        if (chatContainer) {
            // تمرير السكرول إلى الأسفل مباشرة
            chatContainer.scrollTop = chatContainer.scrollHeight;

            // أو مع تأثير سلس (اختياري)
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
    });
</script>
