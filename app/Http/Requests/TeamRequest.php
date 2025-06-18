<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // eventueel aanpassen naar autorisatie logica
    }

    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'string', 'max:255'],             // optioneel, als aanwezig valideren
            'league_id'     => ['sometimes', 'exists:leagues,id'],           // optioneel, als aanwezig valideren
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],  // max 8MB
            'banner_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360'], // max 15MB
        ];
    }
}
