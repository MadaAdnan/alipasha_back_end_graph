<?php

namespace App\Enums;

enum ProductActiveEnum: string
{
    case PENDING = 'product';
    case ACTIVE = 'job';
    case BLOCK = 'search_job';



    public function getLabel()
    {
        return match ($this) {
            self::PENDING => 'بإنتظار التفعيل',
            self::ACTIVE => 'مفعل',
            self::BLOCK => 'محظور',

        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::PENDING => 'fas-hourglass-half',
            self::ACTIVE =>'fas-thumbs-up',
            self::BLOCK =>'fas-ban',

        };
    }

    public function getColor()
    {
        return match ($this) {
            self::PENDING => 'info',
            self::ACTIVE => 'success',
            self::BLOCK => 'danger',

        };
    }
}
