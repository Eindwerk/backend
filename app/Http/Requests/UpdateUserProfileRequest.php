<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'username'      => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:8192'],
            'banner_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:15360'],
        ];
    }
}
