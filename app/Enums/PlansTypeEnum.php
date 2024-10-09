<?php

namespace App\Enums;

enum PlansTypeEnum: string
{
    case PRESENT = 'present';
    case SERVICE = 'service';




    public function getLabel()
    {
        return match ($this) {
            self::PRESENT => 'عروض',
            self::SERVICE => 'خدمات إعلامية',


        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::PRESENT => 'fas-chalkboard-user',
            self::SERVICE =>'fas-video',


        };
    }

    public function getColor()
    {
        return match ($this) {
            self::PRESENT => 'info',
            self::SERVICE => 'success',


        };
    }
}
