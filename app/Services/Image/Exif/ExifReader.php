<?php

namespace App\Services\Image\Exif;

use App\Services\Image\AbstractReader;
use InvalidArgumentException;

class ExifReader extends AbstractReader
{
    /**
     * @param  string|resource  $filename
     */
    protected function __construct(
        protected mixed $filename
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

    public static function fromResource($resource): static
    {
        if (! is_resource($resource)) {
            throw new InvalidArgumentException('Invalid resource');
        }

        return new static($resource);
    }

    public function read(): ?ExifData
    {
        ini_set('exif.encode_unicode', 'UTF-8');
        ini_set('exif.decode_unicode_motorola', 'UCS-2LE');

        $exif = exif_read_data($this->filename, 'ANY_TAG', true);
        if ($exif !== false) {
            return new ExifData($this->sanitizeIptcData($exif));
        }

        return null;
    }
}
