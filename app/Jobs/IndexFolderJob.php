<?php

namespace App\Jobs;

use App\Models\Folder;
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
        private readonly bool $shouldScanSubFolders = false
    ) {
    }

    public function handle(): void
    {
        // TODO Detect all files in the storage folder

        if ($this->shouldScanSubFolders) {
            $this->scanSubFolders();
        }

        $this->indexDetectedFiles();
    }

    /**
     * Dispatch IndexFolderJob for subfolders
     */
    protected function scanSubFolders(): void
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
}
