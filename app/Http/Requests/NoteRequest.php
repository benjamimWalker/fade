<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => ['required', 'string'],
            'view_limit' => ['nullable', 'integer', 'min:1'],
            'ttl' => ['nullable', 'integer', 'max:604700', 'min:360'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
