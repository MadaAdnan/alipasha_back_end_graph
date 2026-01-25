<?php

namespace App\Enums;

enum LevelUserEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case SELLER = 'seller';
    case RESTAURANT = 'restaurant';
    case STAFF = 'staff';


    public function getLabel()
    {
        return match ($this) {
            self::ADMIN => 'مدير',
            self::SELLER => 'متجر',
            self::RESTAURANT => 'مطعم',
            self::USER => 'مستخدم',
            self::STAFF => 'مزظف',
        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::ADMIN => 'fas-user-cog',
            self::SELLER =>'fas-magnifying-glass',
            self::RESTAURANT =>'fas-magnifying-glass',
            self::USER => 'fas-magnifying-glass',
            self::STAFF => 'fas-magnifying-glass',
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::SELLER =>'fas-magnifying-glass',
            self::RESTAURANT =>'fas-magnifying-glass',
            self::USER => 'fas-magnifying-glass',
            self::STAFF => 'fas-magnifying-glass',
        };
    }
}
