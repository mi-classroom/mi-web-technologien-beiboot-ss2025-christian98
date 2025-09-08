<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IptcTagDefinitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'tag' => ['required'],
            'description' => ['nullable'],
            'spec' => ['required'],
            'is_value_editable' => ['boolean'],
            'user_id' => ['nullable', 'exists:users'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
