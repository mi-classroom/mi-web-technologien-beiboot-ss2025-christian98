<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class IndexFolderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        foreach ($this->folder->folders as $subfolder) {
            self::dispatch($subfolder, $this->shouldIndexSubFolders);
        }
    }

    /**
     * Dispatch IndexFileJob for each file
     */
    private function indexDetectedFiles(): void
    {
        foreach ($this->folder->files as $file) {
            IndexFileJob::dispatch($file);
        }
    }

    /**
     * @param Folder $folder
     * @param Filesystem|null $storage
     * @return Collection<Folder>
     */
    public static function scanForSubFolders(Folder $folder, ?Filesystem $storage = null): Collection
    {
        $storage ??= $folder->storageConfig->getStorage();

        return collect($storage->directories($folder->full_path))
            ->map(function (string $dir) use ($folder) {;
                $name = basename($dir);

                return Folder::updateOrCreate([
                    'name' => $name,
                    'storage_config_id' => $folder->storageConfig->id,
                    'parent_id' => $folder->id,
                ]); // TODO: import timestamps from filesystem
            });
    }

    /**
     * @param Folder $folder
     * @param Filesystem|null $storage
     * @return Collection<File>
     */
    public static function scanForFiles(Folder $folder, ?Filesystem $storage = null): Collection
    {
        $storage ??= $folder->storageConfig->getStorage();

        return collect($storage->files($folder->full_path))
            ->map(function (string $file) use ($storage, $folder) {
                $name = basename($file);

                return File::updateOrCreate([
                    'name' => $name,
                    'folder_id' => $folder->id,
                ], [
                    'storage_config_id' => $folder->storageConfig->id,
                    'size' => $storage->size($file),
                    'type' => $storage->mimeType($file),
                ]);
            });
    }
}
