<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'dikonfirmasi';
    case PROCESSING = 'diproses';
    case SHIPPED = 'dikirim';
    case COMPLETED = 'selesai';
    case CANCELLED = 'dibatalkan';

    public function getDisplayText(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu konfirmasi',
            self::CONFIRMED => 'Dikonfirmasi',
            self::PROCESSING => 'Sedang di proses',
            self::SHIPPED => 'Dalam Pengiriman',
            self::COMPLETED => 'Selesal',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::PENDING, self::CONFIRMED]);
    }

    public function canBeCompleted(): bool
    {
        return $this === self::COMPLETED;
    }
}
