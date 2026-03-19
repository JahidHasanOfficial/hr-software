<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }
}
