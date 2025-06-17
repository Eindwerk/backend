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
            'stadium_id' => ['required', 'integer', 'exists:stadia,id'],
            'image' => ['nullable', 'image', 'max:8192'],
        ];
    }
}
