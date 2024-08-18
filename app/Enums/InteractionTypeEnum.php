<?php

namespace App\Enums;

enum InteractionTypeEnum: string
{
    case VISITED = 'visited';
    case FOLLOWER = 'follower';
    case COMMENT = 'comment';



    public function getLabel()
    {
        return match ($this) {
            self::VISITED => 'زيارة',
            self::FOLLOWER => 'متابعة',
            self::COMMENT => 'تعليق',

        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::VISITED => 'fas-square-check',
            self::FOLLOWER =>'fas-list-check',
            self::COMMENT =>'fas-pen',

        };
    }

    public function getColor()
    {
        return match ($this) {
            self::VISITED => 'success',
            self::FOLLOWER => 'warning',
            self::COMMENT => 'info',

        };
    }
}
