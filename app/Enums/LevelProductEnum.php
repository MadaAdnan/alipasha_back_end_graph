<?php

namespace App\Enums;

enum LevelProductEnum: string
{
    case NORMAL = 'normal';
    case SPECIAL = 'special';
    case NEWS = 'news';




    public function getLabel()
    {
        return match ($this) {
            self::NEWS => 'جديد',
            self::NORMAL => 'عادي',
            self::SPECIAL => 'مميز',


        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::NEWS => 'fas-first-order-alt',
            self::NORMAL => 'fas-square-check',
            self::SPECIAL =>'fas-list-check',
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::NEWS => 'success',
            self::NORMAL => 'info',
            self::SPECIAL => 'gold',


        };
    }
}
