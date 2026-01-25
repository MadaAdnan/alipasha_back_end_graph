<?php

namespace App\Enums;

enum CategoryTypeEnum: string
{
    case PRODUCT = 'product';
    case JOB = 'job';
    case SEARCH_JOB = 'search_job';
    case NEWS = 'news';
    case TENDER = 'tender';
    case SERVICE = 'service';
    case RESTAURANT = 'restaurant';


    public function getLabel()
    {
        return match ($this) {
            self::PRODUCT => 'منتجات',
            self::JOB => 'وظائف',
            self::SEARCH_JOB => 'شواغر',
            self::NEWS => 'اخبار',
            self::TENDER => 'مناقصات',
            self::SERVICE => 'خدمات',
            self::RESTAURANT => 'مطاعم',
        };
    }

//    public function getIcon()
//    {
//        return match ($this) {
//            self::ADMIN => 'fas-user-cog',
//            self::SELLER =>'fas-magnifying-glass',
//            self::RESTAURANT =>'fas-magnifying-glass',
//            self::USER => 'fas-magnifying-glass',
//        };
//    }

    public function getColor()
    {
        return match ($this) {
            self::PRODUCT, self::RESTAURANT => 'danger',
            self::JOB => 'warning',
            self::SEARCH_JOB => 'info',
            self::NEWS => 'primary',
            self::TENDER => 'success',
            self::SERVICE => 'gold',
        };
    }
}
