<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexFolderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(
        private readonly Folder $folder,
        private readonly bool   $shouldIndexSubFolders = false
    )
    {
    }

    public function handle(): void
    {
        self::scanForSubFolders($this->folder);
        self::scanForFiles($this->folder);

        if ($this->shouldIndexSubFolders) {
            $this->indexSubFolders();
        }

        $this->indexDetectedFiles();
    }

    /**
     * Dispatch IndexFolderJob for subfolders
     */
    protected function indexSubFolders(): void
    {
        if (! $this->batching()) {
            return;
        }

        foreach ($this->folder->folders as $subfolder) {
            $this->batch()?->add(new IndexFolderJob($subfolder, $this->shouldIndexSubFolders));
        }
    }

    /**
     * Dispatch IndexFileJob for each file
     */
    private function indexDetectedFiles(): void
    {
        if (! $this->batching()) {
            return;
        }

        foreach ($this->folder->files as $file) {
            $this->batch()?->add(new IndexFileJob($file));
        }
    }

    public static function scanForSubFolders(Folder $folder, ?Filesystem $storage = null): void
    {
        $storage ??= $folder->storageConfig->getStorage();

        collect($storage->directories($folder->full_path))
            ->reject(function (string $dir) {
                return in_array($dir, ['.', '..', '.DS_Store', '._.DS_Store'], true);
            })
            ->each(function (string $dir) use ($folder) {
                $name = basename($dir);

                return Folder::updateOrCreate([
                    'name' => $name,
                    'storage_config_id' => $folder->storageConfig->id,
                    'parent_id' => $folder->id,
                ]); // TODO: import timestamps from filesystem
            });
    }

    public static function scanForFiles(Folder $folder, ?Filesystem $storage = null): void
    {
        $storage ??= $folder->storageConfig->getStorage();

        collect($storage->files($folder->full_path))
            ->each(function (string $file) use ($storage, $folder) {
                $name = basename($file);

                File::updateOrCreate([
                    'name' => $name,
                    'folder_id' => $folder->id,
                ], [
                    'storage_config_id' => $folder->storageConfig->id,
                    'size' => rescue(fn () => $storage->size($file), 0),
                    'type' => rescue(fn () => $storage->mimeType($file), 'application/octet-stream'),
                ]);
            });
    }
}
