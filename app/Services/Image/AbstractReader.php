<?php

namespace App\Services\Image;

class AbstractReader
{
    protected function sanitizeIptcData(array $iptcData): array
    {
        // Convert each IPTC value from its original encoding to UTF-8
        foreach ($iptcData as $key => $values) {
            foreach ($values as $index => $value) {
                // Try to detect encoding and convert to UTF-8
                if (! mb_check_encoding($value, 'UTF-8')) {
                    // Try common encodings
                    $encodings = ['ISO-8859-1', 'Windows-1252'];

                    foreach ($encodings as $encoding) {
                        $converted = @mb_convert_encoding($value, 'UTF-8', $encoding);
                        if (mb_check_encoding($converted, 'UTF-8')) {
                            $iptcData[$key][$index] = $converted;
                            break;
                        }
                    }

                    // If still not valid UTF-8, sanitize
                    if (! mb_check_encoding($iptcData[$key][$index], 'UTF-8')) {
                        $iptcData[$key][$index] = mb_convert_encoding($value, 'UTF-8', 'UTF-8//IGNORE');
                    }
                }
            }
        }

        return $iptcData;
    }
}
