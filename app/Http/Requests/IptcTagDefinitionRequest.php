<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IptcTagDefinitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tag' => ['required', 'string', 'max:5'],
            'description' => ['nullable', 'string', 'max:1000'],
            'spec' => ['required', 'array'],
            // "string", "enum", "binary", 'date', 'time', 'number'
            'spec.data_type' => ['required', 'in:string,enum,binary,date,time,number'],
            'spec.required' => ['required', 'boolean'],
            'spec.multiple' => ['required', 'boolean'],
            'spec.min_length' => ['nullable', 'integer', 'min:0'],
            'spec.max_length' => ['nullable', 'integer', 'min:0'],
            'spec.enum_values' => ['nullable', 'array'],
            'spec.enum_values.*' => ['string'],
            'is_value_editable' => ['nullable', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
