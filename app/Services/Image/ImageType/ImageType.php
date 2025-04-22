<?php

namespace App\Services\Image\ImageType;

enum ImageType: int
{
    case GIF = 1;
    case JPEG = 2;
    case PNG = 3;
    case SWF = 4;
    case PSD = 5;
    case BMP = 6;
    case TIFF_II = 7; // (intel byte order)
    case TIFF_MM = 8; // (motorola byte order)
    case JPC = 9;
    case JP2 = 10;
    case JPX = 11;
    case JB2 = 12;
    case SWC = 13;
    case IFF = 14;
    case WBMP = 15;
    case XBM = 16;
    case ICO = 17;
    case WEBP = 18;
    case AVIF = 19;

    public function mimeType(): string
    {
        return image_type_to_mime_type($this->value);
    }

    public function supportsExif(): bool
    {
        return match($this) {
            self::JPEG, self::TIFF_II, self::TIFF_MM => true,
            default => false,
        };
    }

    public function supportsIptc(): true
    {
        return match($this) {
            // https://exiftool.org/TagNames/IPTC.html, JPG, TIFF, PNG, MIFF, PS, PDF, PSD, XCF and DNG
            self::JPEG, self::TIFF_II, self::TIFF_MM, self::PNG, self::PSD => true,
            default => false,
        };
    }
}
