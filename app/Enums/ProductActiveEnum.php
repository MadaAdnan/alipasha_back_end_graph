<?php

namespace App\Enums;

enum ProductActiveEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case BLOCK = 'block';
    case HIDDEN = 'hidden';



    public function getLabel()
    {
        return match ($this) {
            self::PENDING => 'بإنتظار التفعيل',
            self::ACTIVE => 'مفعل',
            self::BLOCK => 'محظور',
            self::HIDDEN => 'مخفي',

        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::PENDING => 'fas-hourglass-half',
            self::ACTIVE =>'fas-thumbs-up',
            self::BLOCK =>'fas-ban',
            self::HIDDEN =>'fas-ban',

        };
    }

    public function getColor()
    {
        return match ($this) {
            self::PENDING => 'info',
            self::ACTIVE => 'success',
            self::BLOCK => 'danger',
            self::HIDDEN => 'warning',

        };
    }
}
