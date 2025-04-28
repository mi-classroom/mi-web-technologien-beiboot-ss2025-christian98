<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class CreateFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1'],
            'files.*.name' => ['filled', 'string', 'max:255'],
            'files.*.file' => ['required', 'file', File::image()],
        ];
    }
}
