<?php

namespace App\Events\Folder;

use App\Enums\FilesystemEventSource;
use App\Models\Folder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FolderDeletedEvent
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
