<?php

namespace App\DTOs;

use App\Http\Requests\prosesPembayaranRequest;

readonly class TransactionData
{
    public function __construct(
        public string $customerName,
        public string $address,
        public string $phone,
        public string $courier,
        public string $paymentMethod,
        public ?string $latitude = null,
        public ?string $longitude = null,
    ) {}

    public static function fromRequest(prosesPembayaranRequest $request): self
    {
        return new self(
            customerName: $request->string('namaAnda')->toString(),
            address: $request->string('alamatAnda')->toString(),
            phone: $request->string('tlp')->toString(),
            courier: $request->string('ekspedisi')->toString(),
            paymentMethod: $request->string('metode')->toString(),
            latitude: $request->float('latitude'),
            longitude: $request->float('longitude'),
        );
    }
}
