<?php

namespace App\Listeners;

use App\Enums\FilesystemEventSource;
use App\Events\FileCreatedEvent;
use App\Jobs\IndexFileJob;
use Illuminate\Support\Facades\Bus;

class CreateFileInStorageListener
{
    public function __construct() {}

    public function handle(FileCreatedEvent $event): void
    {
        if ($event->source === FilesystemEventSource::Import) {
            // If the file was created from an import, we do not need to index it.
            // This is because the import process will handle indexing.
            // This is to avoid double indexing and potential performance issues.
            // TODO consider removing this check if the import process is changed to not handle indexing.
            return;
        }

        $file = $event->file;

        Bus::dispatch(new IndexFileJob($file));
    }
}
