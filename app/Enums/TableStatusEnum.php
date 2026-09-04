<?php

namespace App\Enums;

enum TableStatusEnum: string
{
    case AVAILABLE = 'available';
    case OCCUPIED = 'occupied';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::OCCUPIED => 'Occupied'
        };
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}