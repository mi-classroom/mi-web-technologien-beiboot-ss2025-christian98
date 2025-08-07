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
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is a string
        if (! is_string($value)) {
            $fail('The :attribute must be a string.');

            return;
        }

        // Check if the value matches the expected format "2#XXX"
        if (! preg_match('/^2#(\d{3})$/', $value, $matches)) {
            $fail('The :attribute must be in the format "2#XXX".');

            return;
        }

        // Extract the tag number from the matched value
        $tagNumber = $matches[1];
        $tag = IptcTagEnum::tryfrom($tagNumber);

        // If the tag is null, it means the tag number is not valid
        if ($tag === null) {
            $fail('The :attribute is not a valid IPTC tag.');

            return;
        }
    }
}
