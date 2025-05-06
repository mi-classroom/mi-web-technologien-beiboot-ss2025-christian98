<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFolderRequest extends FormRequest
{
    public function rules(): array
    {
        $parentId = $this->route('folder');

        return [
            'name' => [
                'required', 'string', 'max:255', 'regex:/^(\w+\.?)*\w+$/u',
                Rule::unique('folders', 'name')
                    ->where('parent_id', $parentId),
            ],
        ];
    }
}
