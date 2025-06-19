<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Handled by Sanctum
    }

    public function rules()
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'bio' => ['sometimes', 'string', 'nullable'],
            'city' => ['sometimes', 'string', 'nullable', 'max:100'],
            'country' => ['sometimes', 'string', 'nullable', 'max:100'],
            'availability' => ['sometimes', 'array', 'nullable'],
            'skills_offered' => ['sometimes', 'array', 'nullable'],
            'skills_offered.*' => ['string', 'max:100'],
            'skills_needed' => ['sometimes', 'array', 'nullable'],
            'skills_needed.*' => ['string', 'max:100'],
        ];
    }
}