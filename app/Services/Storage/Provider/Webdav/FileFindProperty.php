<?php

namespace App\Services\Storage\Provider\Webdav;

enum FileFindProperty: string
{
    case Size = '{DAV:}getcontentlength';
    case LastModified = '{DAV:}getlastmodified';
    case MimeType = '{DAV:}getcontenttype';
}
