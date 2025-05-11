<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image'         => 'required|image|mimes:png,jpg,jpeg|max:10240',
            'title'         => 'required|string|max:60',
            'categories'    => 'required|array',
            'categories.*'  => 'required|integer',
            'preview'       => 'required|string|max:112',
            'content'       => 'required|string|max:5000',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'image'         => 'gambar',
            'title'         => 'judul',
            'categories'    => 'kategori',
            'preview'       => 'pratinjau',
            'content'       => 'postingan',
        ];
    }
}
