<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class FolderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function view(User $user, Folder $folder): bool
    {
        return $folder->storageConfig->user_id === $user->id;
    }

    public function create(User $user, Folder $folder): bool
    {
        return $user->hasVerifiedEmail() && Gate::forUser($user)->allows('update', $folder);
    }

    public function update(User $user, Folder $folder): bool
    {
        return $folder->storageConfig->user_id === $user->id;
    }

    public function delete(User $user, Folder $folder): bool
    {
        return $folder->storageConfig->user_id === $user->id && $folder->parent !== null;
    }

    public function restore(User $user, Folder $folder): bool
    {
        return $folder->storageConfig->user_id === $user->id;
    }

    public function forceDelete(User $user, Folder $folder): bool
    {
        return $folder->storageConfig->user_id === $user->id && $folder->parent !== null;
    }
}
