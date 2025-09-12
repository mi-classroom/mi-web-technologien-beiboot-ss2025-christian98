<?php

namespace App\Events\IptcItem;

use App\Enums\FilesystemEventSource;
use App\Models\IptcItem;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IptcItemDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public readonly FilesystemEventSource $source;

    public function __construct(
        public readonly IptcItem $iptcItem,
        ?FilesystemEventSource   $source = null,
    ) {
        $this->source = $source ?? FilesystemEventSource::detect();
    }
}
