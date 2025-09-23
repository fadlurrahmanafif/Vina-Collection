<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class prosesPembayaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'namaAnda' => 'required|string|max:255',
            'alamatAnda' => 'required|string|max:500',
            'tlp' => 'required|string|max:50',
            'ekspedisi' => 'required|in:jnt,jne,pos,sicepat',
            'metode' => 'required|in:cod,dana,gopay,transfer',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric:between:-180,180',
        ];
    }

    public function messages()
    {
        return [
            'namaAnda.required' => 'Nama harus diisi',
            'alamatAnda.required' => 'Alamat harus diisi',
            'tlp.required' => 'Nomor telepon harus diisi',
            'ekspedisi.required' => 'Pilih ekspedisi pengiriman',
            'ekspedisi.in' => 'Ekspedisi tidak valid',
            'metode.required' => 'Pilih metode pembayaran',
            'metode.in' => 'Metode pembayaran tidak valid',
            'latitude.between' => 'Koordinat latitude tidak valid',
            'longitude.between' => 'Koordinat longitude tidak valid',
        ];
    }
}
