<?php

namespace App\Services\Image\Exif;

enum ExifGroup: string
{
    case File = 'FILE';
    case Computed = 'COMPUTED';
    case IFD0 = 'IFD0';
    case Comment = 'COMMENT';
    case Exif = 'EXIF';
}
