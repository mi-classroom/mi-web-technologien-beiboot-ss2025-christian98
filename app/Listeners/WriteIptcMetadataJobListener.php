<?php

namespace App\Listeners;

use App\Enums\FilesystemEventSource;
use App\Events\IptcItem\IptcItemCreatedEvent;
use App\Events\IptcItem\IptcItemDeletedEvent;
use App\Events\IptcItem\IptcItemUpdatedEvent;
use App\Jobs\WriteIptcMetadataJob;

class WriteIptcMetadataJobListener
{
    public function __construct() {}

    public function handle(IptcItemCreatedEvent|IptcItemUpdatedEvent|IptcItemDeletedEvent $event): void
    {
        ray('WriteIptcMetadataJobListener triggered for event', $event);
        if ($event->source === FilesystemEventSource::Import) {
            // If the IptcItem was created/updated/deleted from an import, we do not need to write it to the storage.
            return;
        }

        WriteIptcMetadataJob::dispatch($event->iptcItem->file);
    }
}
