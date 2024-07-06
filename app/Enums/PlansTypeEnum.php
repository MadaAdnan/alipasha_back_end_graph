<?php

namespace App\Enums;

enum PlansTypeEnum: string
{
    case FREE = 'free';
    case MONTH = 'month';




    public function getLabel()
    {
        return match ($this) {
            self::FREE => 'مجاني',
            self::MONTH => 'اشتراك شهري',


        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::FREE => 'fas-handshake',
            self::MONTH =>'fas-sack-dollar',


        };
    }

    public function getColor()
    {
        return match ($this) {
            self::FREE => 'info',
            self::MONTH => 'success',


        };
    }
}
