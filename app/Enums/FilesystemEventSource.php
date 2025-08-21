<?php

namespace App\Enums;

use RuntimeException;

enum FilesystemEventSource
{
    case UI;
    case Import;

    public static function detect(): FilesystemEventSource
    {
        if (request()) {
            return self::UI;
        }

        if (app()->runningInConsole()) {
            return self::Import;
        }

        throw new RuntimeException('Unable to detect source for FileCreatedEvent');
    }
}
