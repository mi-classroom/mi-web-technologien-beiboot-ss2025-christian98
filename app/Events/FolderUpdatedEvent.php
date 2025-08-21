<?php

namespace App\Events;

use App\Enums\FilesystemEventSource;
use App\Models\Folder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FolderUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly FilesystemEventSource $source;

    public function __construct(
        public readonly Folder $folder,
        ?FilesystemEventSource $source = null,
    ) {
        $this->source = $source ?? FilesystemEventSource::detect();
    }
}
