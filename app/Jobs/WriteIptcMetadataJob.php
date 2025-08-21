<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\IptcItem;
use App\Services\Image\Image;
use App\Services\Image\IPTC\IptcData;
use App\Services\Image\IPTC\IptcTag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class WriteIptcMetadataJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly File $file)
    {
    }

    public function handle(): void
    {
        $image = Image::fromProvider($this->file->full_path, $this->file->folder->storageConfig->getStorage());

        if ($image->type()->supportsIptc()) {
            $currentIptc = $image->iptcReader()->read();

            if ($currentIptc === null) {
                $currentIptc = new IptcData();
            }

            $newIptc = $this->file->iptcItems
                ->mapWithKeys(fn(IptcItem $iptcItem) => [$iptcItem->tag => $iptcItem])
                ->sortKeys();

            $toBeDeleted = $currentIptc->collect()->diffKeys($newIptc);

            foreach ($toBeDeleted as $key => $value) {
                $currentIptc->remove($key);
            }

            $newIptc->except([
                IptcTag::Version->toString(),
                IptcTag::ObjectName->toString(),
                '1#000',
                '1#090',
            ])->ray()->each(function (IptcItem $item) use ($currentIptc) {
                $currentIptc->set($item->tag, $item->value);
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
        return $this->file->id;
    }

    /**
     * @return string
     */
    private function diskName(): string
    {
        return 'public';
    }
}
