<?php

namespace App\Http\Requests;

use App\Services\Storage\StorageProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorageConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'provider_type' => ['required', Rule::enum(StorageProvider::class)->except([StorageProvider::Local])],
            'provider_options' => ['required', 'array'],
            'is_editable' => ['boolean'],
        ];
    }
}
