<?php

namespace App\DTOs;

readonly class CartItemData
{
    public function __construct(
        public int $productId,
        public string $productName,
        public int $unitPrice,
        public int $quantity,
        public int $totalPrice,
        public ?string $photo = null,
    ) {}

    public function fromArray(array $data): self
    {
        return new self(
            productId: $data['id_barang'],
            productName: $data['nama_produk'],
            unitPrice: $data['harga_satuan'],
            quantity: $data['stok'],
            totalPrice: $data['total_harga'],
            photo: $data['foto'] ?? null,
        );
    }
}
