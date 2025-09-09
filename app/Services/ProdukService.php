<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

class ProdukService
{
    /**
     * Create a new class instance.
     */
    public function store(array $data, UploadedFile $file)
    {
        $data['foto'] = $file->store('produk','public');

        return Product::create($data);
    }
}
