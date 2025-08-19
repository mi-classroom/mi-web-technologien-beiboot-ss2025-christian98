<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Folder;
use App\Services\Storage\Provider\Directory;
use App\Services\Storage\Provider\File as ProviderFile;
use App\Services\Storage\Provider\FilesystemItem;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

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
        $storage ??= $this->folder->storageConfig->getStorage();
        $dir = $storage->directory($this->folder->full_path);

        $dir?->children()
            ->reject(function (FilesystemItem $item) {
                return in_array($item->name(), ['.', '..', '.DS_Store', '._.DS_Store'], true);
            })
            ->each($this->importFilesystemItem(...));

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
        if ($this->batch() && $this->batching()) {
            foreach ($this->folder->folders as $subfolder) {
                $this->batch()?->add(new IndexFolderJob($subfolder, $this->shouldIndexSubFolders));
            }

            return;
        }

        foreach ($this->folder->folders as $subfolder) {
            Bus::dispatch(new IndexFolderJob($subfolder, $this->shouldIndexSubFolders));
        }
    }

    /**
     * Dispatch IndexFileJob for each file
     */
    private function indexDetectedFiles(): void
    {
        if ($this->batch() && $this->batching()) {
            foreach ($this->folder->files as $file) {
                $this->batch()->add(new IndexFileJob($file));
            }

            return;
        }

        foreach ($this->folder->files as $file) {
            Bus::dispatch(new IndexFileJob($file));
        }
    }

    /**
     * @param FilesystemItem $item
     * @return void
     */
    public function importFilesystemItem(FilesystemItem $item): void
    {
        if ($item instanceof ProviderFile) {
            $this->importFile($item);
        } elseif ($item instanceof Directory) {
            $this->importDirectory($item);
        } else {
            Log::warning("Unexpected FilesystemItem type: " . get_class($item) . " in folder: " . $this->folder->full_path);
        }
    }

    /**
     * @param ProviderFile $file
     * @return void
     */
    private function importFile(ProviderFile $file): void
    {
        File::updateOrCreate([
            'name' => $file->name(),
            'folder_id' => $this->folder->id,
        ], [
            'storage_config_id' => $this->folder->storageConfig->id,
            'size' => $file->size(),
            'type' => $file->mimeType(),
        ]);
    }

    /**
     * @param Directory $directory
     * @return void
     */
    private function importDirectory(Directory $directory): void
    {
        Folder::updateOrCreate([
            'name' => $directory->name(),
            'storage_config_id' => $this->folder->storageConfig->id,
            'parent_id' => $this->folder->id,
        ]); // TODO: import timestamps from filesystem
    }
}
