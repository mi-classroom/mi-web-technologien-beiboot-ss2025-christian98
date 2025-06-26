<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Folder;
use App\Services\FullPathGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $this->scanForSubFolders();
        $this->scanForFiles();

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
            self::dispatch($subfolder);
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

    private function scanForSubFolders(): void
    {
        $storage = $this->folder->storageConfig->getStorage();
        $dirs = $storage->directories($this->folder->full_path);

        foreach ($dirs as $dir) {
            $name = basename($dir);
            Folder::updateOrCreate([
                'name' => $name,
                'storage_config_id' => $this->folder->storageConfig->id,
                'parent_id' => $this->folder->id,
            ]); // TODO: import timestamps from filesystem
        }
    }

    private function scanForFiles(): void
    {
        $strorage = $this->folder->storageConfig->getStorage();
        $files = $strorage->files($this->folder->full_path);

        foreach ($files as $file) {
            $name = basename($file);
            File::updateOrCreate([
                'name' => $name,
                'folder_id' => $this->folder->id,
            ], [
                'size' => $strorage->size($file),
                'type' => $strorage->mimeType($file),
            ]);
        }
    }
}
