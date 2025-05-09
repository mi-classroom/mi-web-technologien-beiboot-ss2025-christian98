<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ImageRule implements ValidationRule
{
    // Standard image formats
    private array $standardImageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp', 'image/svg+xml'];

    // Common raw image formats
    private array $rawImageMimes = [
        'image/x-canon-cr2',    // Canon RAW
        'image/x-nikon-nef',    // Nikon RAW
        'image/x-sony-arw',     // Sony RAW
        'image/x-adobe-dng',    // Adobe DNG
        'image/x-olympus-orf',  // Olympus RAW
        'image/x-panasonic-rw2', // Panasonic RAW
        'image/x-fuji-raf',     // Fujifilm RAW
        'image/x-pentax-pef',   // Pentax RAW
        'image/tiff',           // TIFF (often used for RAW)
        'application/octet-stream', // Generic binary (many RAW formats)
    ];

    private array $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg',
        'cr2', 'cr3', 'nef', 'arw', 'dng', 'orf', 'rw2', 'raf', 'pef', 'tiff', 'tif', 'raw'];

    /**
     * @param  UploadedFile  $value
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $mime = $value->getMimeType();

        if (! in_array($mime, array_merge($this->standardImageMimes, $this->rawImageMimes), true)) {
            $fail('The '.$attribute.' must be a valid image or raw image format.');
        }

        // Additional check for file extensions
        $extension = strtolower($value->getClientOriginalExtension());

        if (! in_array($extension, $this->validExtensions, true)) {
            $fail('The '.$attribute.' must have a valid image extension.');
        }
    }
}
