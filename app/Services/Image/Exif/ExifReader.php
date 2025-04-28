<?php

namespace App\Services\Image\Exif;

use InvalidArgumentException;
use RuntimeException;

class ExifReader
{
    /**
     * @param string|resource $filename
     */
    protected function __construct(
        protected mixed $filename
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

    public static function fromResource($resource): static
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('Invalid resource');
        }

        return new static($resource);
    }

    public function read(): ExifData
    {
        $exif = exif_read_data($this->filename, 'EXIF', true);
        if($exif !== false) {
            return new ExifData($exif);
        }

        return new ExifData([]);

        throw new RuntimeException('No Exif data found');
    }
}
