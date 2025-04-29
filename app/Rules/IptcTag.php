<?php

namespace App\Rules;

use App\Services\Image\IPTC\IptcTag as IptcTagEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class IptcTag implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a string.');

            return;
        }

        ray($value);
        if (! preg_match('/^2#(\d{3})$/', $value, $matches)) {
            $fail('The :attribute must be in the format "2#XXX".');

            return;
        }
        ray($matches);

        $tagNumber = $matches[1];
        $tag = IptcTagEnum::tryfrom($tagNumber);

        if ($tag === null) {
            $fail('The :attribute is not a valid IPTC tag.');

            return;
        }
    }
}
