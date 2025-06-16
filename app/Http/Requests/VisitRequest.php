<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VisitRequest extends FormRequest
{
    /**
     * Bepaal of de gebruiker gemachtigd is om dit verzoek uit te voeren.
     */
    public function authorize(): bool
    {
        return Auth::check(); // of gewoon: return true;
    }

    /**
     * Valideer de regels voor het verzoek.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'game_id'    => ['required', 'exists:games,id'],
            'visited_at' => ['nullable', 'date'],
            'notes'      => ['nullable', 'string'],
        ];
    }
}
