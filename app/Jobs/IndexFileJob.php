<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\IptcTagDefinition;
use App\Services\Image\Image;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexFileJob implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(public readonly File $file)
    {
    }

    public function handle(): void
    {
        // Index creation time
        // Index last modified time
        self::indexIptcMetadata($this->file);
    }

    public static function indexIptcMetadata(File $file, ?Filesystem $storage = null): void
    {
        $storage ??= $file->folder->storageConfig->getStorage();

        if (
            $storage instanceof FilesystemAdapter
            && ! preg_match('/image\/.+/', rescue(fn () => $storage->mimeType($file->full_path), 'application/octet-stream'))
        ) {
            // If the file is not an image, skip downloading it
            return;
        }

        $image = Image::fromDisk($file->full_path, $storage);

        if (!$image->type()->supportsIptc()) {
            return;
        }

        $iptcData = $image->iptc();

        if ($iptcData === null) {
            return;
        }

        $iptcData->collect()
            ->sortKeys()
            ->each(function (array $value, string $tag) use ($file) {
                $dbTag = IptcTagDefinition::findByTag($tag, $file->storageConfig->user);

                $file->iptcItems()->updateOrCreate(
                    ['iptc_tag_definition_id' => $dbTag->id],
                    ['value' => $value]
                );
            });
    }

    public function uniqueId(): string
    {
        return $this->file->id;
    }
}
