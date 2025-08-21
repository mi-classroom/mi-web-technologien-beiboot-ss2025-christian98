<?php

namespace App\Listeners\Folder;

use App\Enums\FilesystemEventSource;
use App\Events\FolderUpdatedEvent;

/**
 * This listener is responsible for updating the folder in the storage when the folder is updated.
 * It will rename the directory in the storage if the folder name or parent_id was changed.
 * It will not do anything if the folder was created from an import, as the folder is not created in the storage in that case.
 * It is not queued to ensure that we have access to the original folder path.
 */
class UpdateFolderInStorageListener
{
    public function handle(FolderUpdatedEvent $event): void
    {
        if ($event->source === FilesystemEventSource::Import) {
            // If the folder was created from an import, we do not need to create it in the storage.
            return;
        }

        $folder = $event->folder;
        if ($folder->wasChanged('name', 'parent_id')) {
            // If the folder name or parent_id was changed, we need to rename the directory in
            // the storage.
            $storage = $folder->storageConfig->getStorage();
            $oldPath = $folder->getOriginal('full_path');
            $newPath = $folder->full_path;

            if ($storage->exists($oldPath)) {
                // Rename the directory in the storage
                $storage->move($oldPath, $newPath);
            } else {
                // If the old path does not exist, we can just create the new path
                $storage->makeDirectory($newPath);
            }
        }
    }
}
