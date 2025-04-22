<?php

namespace App\Services\Image\IPTC;

use InvalidArgumentException;
use RuntimeException;

class IptcReader
{
    /**
     * @param string $filename
     */
    protected function __construct(
        protected string $filename
    ) {
    }

    public static function fromFilename(string $filename): static
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File does not exist');
        }

        if (!is_readable($filename)) {
            throw new InvalidArgumentException('File is not readable');
        }

        return new static($filename);
    }

    public function read(): IptcData
    {
        $size = getimagesize($this->filename, $info);
        if(isset($info['APP13']))
        {
            $iptc = iptcparse($info['APP13']);
            return new IptcData($this->sanitizeIptcData($iptc));
        }

        return new IptcData([]);

        throw new RuntimeException('No IPTC data found');
    }

    protected function sanitizeIptcData(array $iptcData): array
    {
        // Convert each IPTC value from its original encoding to UTF-8
        foreach($iptcData as $key => $values) {
            foreach($values as $index => $value) {
                if($this->isBinaryData($value)) {
                    // Handle as binary (e.g., skip, show as hex, etc.)
                    $iptcData[$key][$index] = "[BINARY DATA: " . strlen($value) . " bytes]";
                    break;
                }

                // Try to detect encoding and convert to UTF-8
                if(!mb_check_encoding($value, 'UTF-8')) {
                    // Try common encodings
                    $encodings = array('ISO-8859-1', 'Windows-1252');

                    foreach($encodings as $encoding) {
                        $converted = @mb_convert_encoding($value, 'UTF-8', $encoding);
                        if(mb_check_encoding($converted, 'UTF-8')) {
                            $iptcData[$key][$index] = $converted;
                            break;
                        }
                    }

                    // If still not valid UTF-8, sanitize
                    if(!mb_check_encoding($iptcData[$key][$index], 'UTF-8')) {
                        $iptcData[$key][$index] = mb_convert_encoding($value, 'UTF-8', 'UTF-8//IGNORE');
                    }
                }
            }
        }

        return $iptcData;
    }

    protected function isBinaryData($data) {
        // Look for non-printable characters (except for whitespace)
        return preg_match('/[^\P{C}\s]/u', $data) === 1;
    }
}
