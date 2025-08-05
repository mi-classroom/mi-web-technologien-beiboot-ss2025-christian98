<?php

namespace App\Services\Image\IPTC;

use App\Services\Image\AbstractReader;
use InvalidArgumentException;

class IptcReader extends AbstractReader
{
    protected function __construct(
        protected string $filename
    ) {}

    public static function fromFilename(string $filename): static
    {
        if (! file_exists($filename)) {
            throw new InvalidArgumentException('File does not exist');
        }

        if (! is_readable($filename)) {
            throw new InvalidArgumentException('File is not readable');
        }

        return new static($filename);
    }

    public function read(): ?IptcData
    {
        $size = getimagesize($this->filename, $info);
        if (isset($info['APP13'])) {
            $iptc = iptcparse($info['APP13']);

            if ($iptc === false) {
                return null; // No IPTC data found
            }

            return new IptcData($this->sanitizeIptcData($iptc));
        }

        return null;
    }
}
