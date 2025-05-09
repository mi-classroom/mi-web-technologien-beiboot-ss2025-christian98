<?php

namespace App\Services\Image\ImageType;

use InvalidArgumentException;
use RuntimeException;

class ImageTypeDetector
{
    public function detect(string $filename): ImageType
    {
        if (! file_exists($filename)) {
            throw new InvalidArgumentException('File does not exist');
        }

        if (! is_readable($filename)) {
            throw new InvalidArgumentException('File is not readable');
        }

        $imagetype = exif_imagetype($filename);

        if ($imagetype !== false) {
            return ImageType::from($imagetype);
        }

        return ImageType::Unknown;
    }
}
