<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIptcItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'value' => ['required', 'array'],
            'value.*' => ['required', 'string', 'max:255'],
        ];
    }
}
