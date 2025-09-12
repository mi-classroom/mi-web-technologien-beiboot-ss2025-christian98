<?php

namespace App\Events;

use App\Enums\FilesystemEventSource;
use App\Models\File;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly FilesystemEventSource $source;

    public function __construct(
        public readonly File $file,
        ?FilesystemEventSource $source = null,
    ) {
        $this->source = $source ?? FilesystemEventSource::detect();
    }
}
