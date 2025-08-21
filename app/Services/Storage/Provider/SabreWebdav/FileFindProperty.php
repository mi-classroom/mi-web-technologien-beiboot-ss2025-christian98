<?php

namespace App\Services\Storage\Provider\SabreWebdav;

enum FileFindProperty: string
{
    case Size = '{DAV:}getcontentlength';
    case LastModified = '{DAV:}getlastmodified';
    case MimeType = '{DAV:}getcontenttype';
}
