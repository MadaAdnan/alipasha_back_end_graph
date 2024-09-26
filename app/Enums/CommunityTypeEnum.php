<?php

namespace App\Enums;

enum CommunityTypeEnum: string
{
    case CHANNEL = 'channel';
    case CHAT = 'chat';
    case GROUP = 'group';



    public function getLabel()
    {
        return match ($this) {
            self::CHANNEL => 'قناة',
            self::CHAT => 'محادثة',
            self::GROUP => 'مجموعة',

        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::CHANNEL => 'fas-satellite-dish',
            self::CHAT =>'fas-comments',
            self::GROUP =>'fas-users',

        };
    }

    public function getColor()
    {
        return match ($this) {
            self::CHANNEL => 'danger',
            self::CHAT => 'warning',
            self::GROUP => 'info',

        };
    }
}
