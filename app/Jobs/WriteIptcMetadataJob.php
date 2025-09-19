<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\IptcItem;
use App\Services\Image\Image;
use App\Services\Image\IPTC\IptcData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WriteIptcMetadataJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly File $file) {}

    public function handle(): void
    {
        $image = Image::fromProvider($this->file->full_path, $this->file->folder->storageConfig->getStorage());

        if ($image->type()->supportsIptc()) {
            $currentIptc = $image->iptcReader()->read();

            if ($currentIptc === null) {
                $currentIptc = new IptcData;
            }

            $newIptc = $this->file->iptcItems()->with('tagDefinition')->get()
                ->mapWithKeys(fn (IptcItem $iptcItem) => [$iptcItem->tagDefinition->tag => $iptcItem])
                ->sortKeys();

            $toBeDeleted = $currentIptc->collect()->diffKeys($newIptc);

            foreach ($toBeDeleted as $key => $value) {
                $currentIptc->remove($key);
            }

            // TODO: Maybe get from DB definitions for IPTC tags
            // and filter out the ones that are not allowed to be set.
            $newIptc->except([
                '1#000',
                '1#090',
                '2#000', // IPTC Application Record Version
                '2#001', // IPTC Object Name
            ])->ray()->each(function (IptcItem $item) use ($currentIptc) {
                $currentIptc->set($item->tagDefinition->tag, $item->value);
            });

            // Save the IPTC data back to the image
            $image->writeIptc($currentIptc);
            $this->file->folder->storageConfig->getStorage()->upload($this->file->full_path, $image->contents());
        }

        $this->file->touch();
        IndexFileJob::dispatch($this->file);
    }

    public function uniqueId(): string
    {
        return (string) $this->file->id;
    }
}
