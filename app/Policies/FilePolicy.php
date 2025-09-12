<?php

namespace App\Policies;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Folder $folder): bool
    {
        return $user->can('view', $folder);
    }

    public function view(User $user, File $file): bool
    {
        return $user->can('view', $file->folder);
    }

    public function create(User $user, Folder $folder): bool
    {
        return $user->can('update', $folder);
    }

    public function update(User $user, File $file): bool
    {
        return $user->can('update', $file->folder);
    }

    public function delete(User $user, File $file): bool
    {
        return $user->can('update', $file->folder);
    }

    public function restore(User $user, File $file): bool
    {
        return $user->can('update', $file->folder);
    }

    public function forceDelete(User $user, File $file): bool
    {
        return $user->can('update', $file->folder);
    }
}
