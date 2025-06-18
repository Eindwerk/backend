<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'game_id' => ['required', 'integer', 'exists:games,id'],
            'comments' => ['nullable', 'string'],       // comments kolom is tekst
            'image' => ['nullable', 'image', 'max:8192'], // max 8MB
        ];
    }
}
