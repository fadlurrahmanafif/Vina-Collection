<?php

namespace App\Services;

use App\Contracts\DataProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

class DataProductService
{
    public function __construct(
        private readonly DataProductRepositoryInterface $dataProductRepo,
    ) {}

    public function store(array $data, ?UploadedFile $photo): void
    {
        if ($photo) {
            $data['foto'] = $this->uploadPhoto($photo);
        }

        $this->dataProductRepo->create($data);
    }

    public function update(int $productId, array $data, ?UploadedFile $photo = null): void
    {
        if ($photo) {
            $data['foto'] = $this->uploadPhoto($photo);
        }

        $this->dataProductRepo->update($productId, $data);
    }

    public function delete(int $productId): void
    {
        $this->dataProductRepo->delete($productId);
    }

    public function getProductById(int $productId): Product
    {
        return $this->dataProductRepo->findById($productId);
    }

    private function uploadPhoto(UploadedFile $photo): string
    {
        $filename = date('Ymd') . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('storage/produk'), $filename);
        return $filename;
    }
}
