<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PREPARING = 'preparing';
    case READY = 'ready';
    case SERVED = 'served';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING    => 'Pending',
            self::CONFIRMED  => 'Confirmed',
            self::PREPARING  => 'Preparing',
            self::READY      => 'Ready',
            self::SERVED     => 'Served',
            self::COMPLETED  => 'Completed',
            self::CANCELLED  => 'Cancelled',
        };
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}