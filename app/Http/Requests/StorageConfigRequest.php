<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'provider_type' => ['required'],
            'options' => ['required'],
            'is_editable' => ['boolean'],
        ];
    }
}
