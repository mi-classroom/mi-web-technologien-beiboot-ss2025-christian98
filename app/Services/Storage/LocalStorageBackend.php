<?php

namespace App\Services\Storage;

use App\Models\Folder;
use App\Services\Cache\FolderCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RuntimeException;

class LocalStorageBackend implements StorageBackend
{
    public function getFolder(string $path): Entities\Folder
    {
        // retrieve folder id from cache
        if ($folderId = app(FolderCache::class)->getFolderId(Str::start($path, '/'))) {
            return Entities\Folder::from(Folder::findOrFail($folderId)->loadMissing('folders', 'files', 'parent'));
        }

        throw new RuntimeException('Folder not found');
    }

    public function getFile(string $path): Entities\File
    {
        return Entities\File::from(
            app(FolderCache::class)->getFolderId(Str::start($path, '/'))
                ?->files
                ->firstWhere('path', Str::afterLast($path, '/'))
        );
    }

    public function getRoot(): Entities\Folder
    {
        $user = Auth::user();
        $folder = $user->folders()->with(['files', 'folders', 'parent'])->firstOrFail();

        return Entities\Folder::from($folder);
    }
}
