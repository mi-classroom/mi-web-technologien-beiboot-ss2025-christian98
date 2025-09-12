<?php

namespace App\Http\Requests;

use App\Models\Folder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFolderRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var Folder|number $parent */
        $parent = $this->route('folder');
        /** @var number $parentId */
        $parentId = $parent instanceof Folder ? $parent->id : $parent;

        return [
            'name' => [
                'required', 'string', 'max:255', 'regex:/^([\w\s]+\.?)*[\w\s]+$/u',
                Rule::unique(Folder::class, 'name')
                    ->where('parent_id', $parentId),
            ],
        ];
    }
}
