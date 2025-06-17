<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStadiumProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['sometimes', 'string', 'max:255'],
            'team_id'           => ['sometimes', 'integer', 'exists:teams,id'],
            'profile_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'banner_image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360'],
            'location'          => ['sometimes', 'array'],
            'location.latitude' => ['required_with:location', 'numeric'],
            'location.altitude' => ['required_with:location', 'numeric'],
        ];
    }
}
