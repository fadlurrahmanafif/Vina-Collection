<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_produk' => 'required',
            'kategori' => 'required',
            'tipe' => 'required',
            'harga' => 'required|numeric',
            'foto' => 'required|image|max:2048',
            'stok' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'tipe.required' => 'Tipe wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'foto.required' => 'Foto wajib diisi',
            'stok.required' => 'Stok wajib diisi'
        ];
    }

    // Override method failedValidation
    protected function failedValidation(Validator $validator)
    {
        // Custom redirect dengan session untuk modal
        $response = redirect()
            ->route('product')
            ->withErrors($validator)
            ->withInput()
            ->with('showModal', true);

        throw new HttpResponseException($response);
    }
}
