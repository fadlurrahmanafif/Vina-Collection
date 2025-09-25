<?php

namespace App\ValueObjects;

readonly class TransactionSummary
{
    public function __construct(
        public int $subtotal,
        public int $shippingCost,
        public int $serviceFee,
        public int $totalAmount,
        public int $totalQuantity,
    ) {}

    public function formatSubtotal(): string
    {
        return 'Rp' . number_format($this->subtotal, 0, ',', '.');
    }

    public function formatTotal(): string
    {
        return 'Rp' . number_format($this->totalAmount, 0, ',', '.');
    }
}
