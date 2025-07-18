<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueUsernameAcrossTables;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // user moet ingelogd zijn
    }

    public function rules(): array
    {
        return [
            'username'      => [
                'nullable',
                'string',
                'max:255',
                new UniqueUsernameAcrossTables($this->user()->id), // custom rule checkt uniekheid over tabellen
            ],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],  // max 8MB
            'banner_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360'], // max 15MB
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'This username is already taken or conflicts with a team or stadium name.',
            'profile_image.max' => 'The profile image may not be greater than 8MB.',
            'banner_image.max' => 'The banner image may not be greater than 15MB.',
        ];
    }
}
