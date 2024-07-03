<?php

namespace App\Enums;

enum LevelSellerEnum: int
{
    case GOLD = 1;
    case SILVER = 2;
    case BRONZE = 3;
    case PLATINUM = 4;


    public function getLabel()
    {
        return match ($this) {
            self::GOLD => 'ذهبي',
            self::SILVER => 'فضي',
            self::BRONZE => 'برونزي',
            self::PLATINUM => 'بلاتيني',
        };
    }

   /* public function getIcon()
    {
        return match ($this) {
            self::GOLD => 'fas-user-cog',
            self::SILVER => 'fas-magnifying-glass',
            self::BRONZE => 'fas-magnifying-glass',
            self::DEFAULT => 'fas-magnifying-glass',
        };
    }*/

    public function getColor()
    {
        return match ($this) {
            self::GOLD =>'gold',
            self::SILVER => 'silver',
            self::BRONZE => 'bronze',
            self::PLATINUM => 'platinum',
        };
    }
}
