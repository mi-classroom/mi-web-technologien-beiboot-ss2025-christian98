<?php

namespace App\Http\Requests;

use App\Models\IptcTagDefinition;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateIptcItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tag' => ['required', Rule::exists(IptcTagDefinition::class, 'id')->where(function (Builder $query) {
                return $query->where('user_id', $this->user()->id)->orWhereNull('user_id');
            })],
            'value' => ['required', 'array'],
            'value.*' => ['required', 'string', 'max:255'],
        ];
    }
}
