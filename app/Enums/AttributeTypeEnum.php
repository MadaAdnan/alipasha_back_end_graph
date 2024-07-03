<?php

namespace App\Enums;

enum AttributeTypeEnum: string
{
    case LIMIT = 'limit';
    case MULTIPLE = 'multiple';
    case VALUE = 'value';



    public function getLabel()
    {
        return match ($this) {
            self::LIMIT => 'محدد',
            self::MULTIPLE => 'متعدد',
            self::VALUE => 'قيمة',

        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::LIMIT => 'fas-square-check',
            self::MULTIPLE =>'fas-list-check',
            self::VALUE =>'fas-pen',

        };
    }

    public function getColor()
    {
        return match ($this) {
            self::LIMIT => 'success',
            self::MULTIPLE => 'warning',
            self::VALUE => 'info',

        };
    }
}
