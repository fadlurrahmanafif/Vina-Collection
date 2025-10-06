<?php

namespace App\Services;

use App\Contracts\DataProductRepositoryInterface;
use Illuminate\Http\UploadedFile;

class DataProductService
{
    public function __construct(
        private readonly DataProductRepositoryInterface $dataProductRepo,
    ) {}

    public function store(array $data, ?UploadedFile $photo)
    {
        if ($photo) {
            $data['foto'] = $this->uploadPhoto($photo);
        }

        $this->dataProductRepo->create($data);
    }

    public function update(int $productId, array $data, ?UploadedFile $photo = null)
    {
        if ($photo) {
            $data['foto'] = $this->uploadPhoto($photo);
        }

        $this->dataProductRepo->update($productId, $data);
    }

    public function delete(int $productId)
    {
        $this->dataProductRepo->delete($productId);
    }

    private function uploadPhoto(UploadedFile $photo)
    {
        $filename = date('Ymd') . '_' . $photo->getClientOriginalName();
        $photo->move(public_path('storage/produk'), $filename);
        return $filename;
    }
}
