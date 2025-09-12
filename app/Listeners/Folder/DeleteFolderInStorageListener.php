<?php

namespace App\Listeners\Folder;

use App\Enums\FilesystemEventSource;
use App\Events\Folder\FolderDeletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteFolderInStorageListener implements ShouldQueue
{
    public function handle(FolderDeletedEvent $event): void
    {
        if ($event->source === FilesystemEventSource::Import) {
            // If the folder was created from an import, we do not need to create it in the storage.
            return;
        }

        $event->folder->storageConfig->getStorage()->directory($event->folder->full_path)?->delete();
    }
}
