<?php

namespace App\Enums;

enum ClientStatus: string
{
    case LEAD = 'lead';
    case ACTIVE = 'active';
    case FORMER = 'former';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
