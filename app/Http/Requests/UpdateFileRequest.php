<?php

namespace App\Http\Requests;

use App\Rules\IptcTag;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['filled', 'string', 'max:255'],
            'folder_id' => ['nullable', 'exists:folders,id'],
            'meta_data' => ['filled', 'array'],
            'meta_data.iptc' => ['filled', 'array'],
            'meta_data.iptc.*' => ['filled', 'array'],
            'meta_data.iptc.*.tag' => ['filled', 'string', new IptcTag],
            'meta_data.iptc.*.value' => ['nullable', 'array'],
            'meta_data.iptc.*.value.*' => ['required', 'string', 'max:255'], // TODO are there length restrictions?
        ];
    }
}
