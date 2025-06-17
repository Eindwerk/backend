<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StadiumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'team_id'       => ['required', 'integer', 'exists:teams,id'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'banner_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360'],
            'latitude'      => ['required', 'numeric'],
            'longitude'     => ['required', 'numeric'],
        ];
    }
}
