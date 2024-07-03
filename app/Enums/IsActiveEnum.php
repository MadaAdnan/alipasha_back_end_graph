<?php

namespace App\Enums;

enum IsActiveEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;


    public function getLabel()
    {
        return match ($this) {
            self::INACTIVE => 'غير مفعل',
            self::ACTIVE => 'مفعل',

        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::ACTIVE => 'fas-circle-check',
            self::INACTIVE => 'fas-ban',

        };
    }

    public function getColor()
    {
        return match ($this) {
            self::INACTIVE => 'danger',
            self::ACTIVE => 'success',
        };
    }
}
