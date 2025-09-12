<?php

namespace App\Listeners\Folder;

use App\Enums\FilesystemEventSource;
use App\Events\Folder\FolderCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFolderInStorageListener implements ShouldQueue
{
    public function handle(FolderCreatedEvent $event): void
    {
        if ($event->source === FilesystemEventSource::Import) {
            // If the folder was created from an import, we do not need to create it in the storage.
            return;
        }

        $event->folder->storageConfig->getStorage()->makeDirectory($event->folder->full_path);
    }
}
