<?php

namespace App\Enums;

enum PartnerTypeEnum: string
{
    case PARTNER = 'partner';
    case SELLER = 'seller';




    public function getLabel()
    {
        return match ($this) {
            self::PARTNER => 'وكيل',
            self::SELLER => 'متجر',


        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::PARTNER => 'fas-square-check',
            self::SELLER =>'fas-list-check',


        };
    }

    public function getColor()
    {
        return match ($this) {
            self::PARTNER => 'success',
            self::SELLER => 'warning',


        };
    }
}
