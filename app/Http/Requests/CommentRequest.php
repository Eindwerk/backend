<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Autoriseer alle gebruikers. Pas aan indien nodig.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validatieregels voor het aanmaken van een comment.
     */
    public function rules(): array
    {
        return [
            'post_id' => ['required', 'exists:posts,id'],
            'comment' => ['required', 'string', 'max:1000'],
        ];
    }
}
