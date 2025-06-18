<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // evt aanpassen voor autorisatie
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'league_id'     => ['required', 'exists:leagues,id'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],  // max 8MB
            'banner_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360'], // max 15MB
        ];
    }
}
