<?php

namespace App\Services\Image\Exif;

enum ExifTag: string
{
    const ApertureFNumber  = 'ApertureFNumber';
    const Artist           = 'Artist';
    const Caption          = 'caption';
    const ColorSpace       = 'ColorSpace';
    const Copyright        = 'copyright';
    const DateTimeOriginal = 'DateTimeOriginal';
    const Credit           = 'credit';
    const ExposureTime     = 'ExposureTime';
    const FileSize         = 'FileSize';
    const FocalLength      = 'FocalLength';
    const FocusDistance    = 'FocusDistance';
    const Headline         = 'headline';
    const Height           = 'Height';
    const ISOSpeedRatings  = 'ISOSpeedRatings';
    const Jobtitle         = 'jobtitle';
    const Keywords         = 'keywords';
    const MimeType         = 'MimeType';
    const Model            = 'Model';
    const Orientation      = 'Orientation';
    const Software         = 'Software';
    const Source           = 'source';
    const Title            = 'title';
    const Width            = 'Width';
    const XResolution      = 'XResolution';
    const YResolution      = 'YResolution';
    const GPSLatitude      = 'GPSLatitude';
    const GPSLongitude     = 'GPSLongitude';

    public function toString(): string
    {
        return $this->value;
    }
}
