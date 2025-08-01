<?php

namespace App\Jobs;

use App\Models\Folder;
use App\Models\StorageConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class IndexStorageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600; // 1 hour timeout for indexing

    public function __construct(
        public readonly StorageConfig $storageConfig,
    ) {
    }

    public function handle(): void
    {
        $this->storageConfig->update(['status' => 'indexing']);
        // Index the root folder of the storage config
        $rootFolder = Folder::firstOrCreate([
            'name' => '',
            'parent_id' => null,
            'storage_config_id' => $this->storageConfig->id,
        ]); // TODO: import timestamps from filesystem
        $this->storageConfig->root_folder_id = $rootFolder->id;
        $this->storageConfig->save();
        ray($rootFolder);

        $this->indexFolder($rootFolder);
        $this->storageConfig->update([
            'status' => 'connected',
            'last_indexed_at' => now(),
        ]);
    }

    protected function indexFolder(Folder $folder): void
    {
        ray($folder)->label('Indexing folder');
        $storage = $folder->storageConfig->getStorage();

        $files = IndexFolderJob::scanForFiles($folder, $storage);
        foreach ($files as $file) {
            ray($file)->label('Indexing file');
            IndexFileJob::indexIptcMetadata($file, $storage);
        }

        $folders = IndexFolderJob::scanForSubFolders($folder, $storage);
        foreach ($folders as $subFolder) {
            ray($subFolder)->label('Indexing subfolder');
            $this->indexFolder($subFolder);
        }
    }

    public function failed(?Throwable $exception): void
    {
        $this->storageConfig->update(['status' => 'error']);
    }

    public function tries(): int
    {
        return 5;
    }

    public function backoff(): array
    {
        return [60, 600, 3600, 60*60*12, 60*60*24]; // Retry after 1 minute, 10 minutes, and 1 hour, 12 hours, 24 hours
    }
}
