<?php

namespace App\Enums;

enum FilesystemEventSource
{
    case UI;
    case Import;

    public static function detect(): FilesystemEventSource
    {
        // The order matters here!
        // Somehow Laravel also injects a Request instance when running in console
        if (app()->runningInConsole()) {
            return self::Import;
        }

        // We have no other way to detect the source of the event
        // We always get a request instance here
        return self::UI;
    }
}
