<?php

namespace App\Enums;

enum LevelProductEnum: string
{
    case NORMAL = 'normal';
    case SPECIAL = 'special';




    public function getLabel()
    {
        return match ($this) {
            self::NORMAL => 'عادي',
            self::SPECIAL => 'مميز',


        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::NORMAL => 'fas-square-check',
            self::SPECIAL =>'fas-list-check',
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::NORMAL => 'info',
            self::SPECIAL => 'gold',


        };
    }
}
