<?php

namespace App\Enum;

enum BlameCardType: string
{
    case White = 'white';
    case Yellow = 'yellow';
    case Red = 'red';

    public function label(): string
    {
        return match ($this) {
            self::White => 'Carton blanc',
            self::Yellow => 'Carton jaune',
            self::Red => 'Carton rouge',
        };
    }
}
