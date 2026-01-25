<?php

namespace App\Enums;

enum PlansDurationEnum: string
{
    case MONTH = 'month';
    case YEAR = 'year';
    case FREE = 'free';




    public function getLabel()
    {
        return match ($this) {
            self::MONTH => 'شهري',
            self::YEAR => 'سنوي',
            self::FREE => 'مجاني',


        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::MONTH => 'fas-calendar-days',
            self::YEAR =>'fas-calendar',
            self::FREE =>'fas-gift',


        };
    }

    public function getColor()
    {
        return match ($this) {
            self::FREE => 'info',
            self::MONTH => 'success',
            self::YEAR => 'danger',


        };
    }
}
