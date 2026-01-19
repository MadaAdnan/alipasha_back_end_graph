@props([
    'community'=>null,
    'communityId'=>null
])
@php

    $isActive = $communityId != null && $community?->id == $communityId;
@endphp

<a class="category-item-link {{ $isActive ? 'active' : '' }}" href="{{route('category.show',$community?->id)}}">
    <div class="category-item-icon">
        @switch($community->type)
            @case(\App\Enums\CommunityTypeEnum::CHAT->value):
            <i class="fa-solid fa-comments"></i>
            @break
            @case(\App\Enums\CommunityTypeEnum::GROUP->value):
            <i class="fa-solid fa-users-between-lines"></i>
            @break
            @case(\App\Enums\CommunityTypeEnum::CHANNEL->value):
            <i class="fa-solid fa-bullhorn"></i>
            @break

        @endswitch
        <span>{{ $community?->name }} <small class="text-red">
                (
             @switch($community->type)
                    @case(\App\Enums\CommunityTypeEnum::CHAT->value):
                    محادثة
                    @break
                    @case(\App\Enums\CommunityTypeEnum::GROUP->value):
                   مجموعة
                    @break
                    @case(\App\Enums\CommunityTypeEnum::CHANNEL->value):
                    قناة
                    @break

                @endswitch
                    )
            </small></span>
    </div>
    <div class="category-item-title">
        <i class="fas fa-chevron-left"></i>
    </div>
</a>



