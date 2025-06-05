<?php

namespace App\Jobs;

use App\Models\File;
use App\Services\Image\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexFileJob implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly File $file)
    {
    }

    public function handle(): void
    {
        // Index creation time
        // Index last modified time
        $this->indexIptcMetadata();
    }

    private function indexIptcMetadata(): void
    {
        $iptcData = Image::fromDisk($this->file->path, 'public')->iptc();

        if ($iptcData === null) {
            return;
        }

        $iptcData->collect()->sortKeys()->each(function (array $value, string $tag) {
            $this->file->iptcItems()->updateOrCreate(
                ['tag' => $tag],
                ['value' => $value]
            );
        });
    }

    public function uniqueId(): string
    {
        return $this->file->id;
    }
}
