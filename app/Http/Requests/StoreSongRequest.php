<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah diproteksi oleh middleware 'auth'
    }

    public function rules(): array
    {
        return [
            'youtube_url' => ['required', 'string', 'max:200'],
            'pesan'       => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'youtube_url.required' => 'Link atau ID YouTube wajib diisi.',
            'youtube_url.max'      => 'Input terlalu panjang.',
            'pesan.max'            => 'Pesan / salam maksimal 255 karakter.',
        ];
    }
}
