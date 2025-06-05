<?php

namespace App\Http\Requests;

use App\Rules\IptcTag;
use Illuminate\Foundation\Http\FormRequest;

class CreateIptcItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tag' => ['required', new IptcTag()],
            'value' => ['required', 'array'],
            'value.*' => ['required', 'string', 'max:255'],
        ];
    }
}
