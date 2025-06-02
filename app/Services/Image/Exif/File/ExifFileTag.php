<?php

namespace App\Services\Image\Exif\File;

enum ExifFileTag: string
{
    case FileName = 'FileName';
    case FileDateTime = 'FileDateTime';
    case FileSize = 'FileSize';
    case FileType = 'FileType';
    case MimeType = 'MimeType';
    case SectionsFound = 'SectionsFound';
}
