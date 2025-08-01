<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {
        return $user->hasVerifiedEmail();
    }

    public function view(User $user, Folder $folder): bool {
        return $folder->storageConfig->user_id === $user->id || ($folder->parent && $user->can('view', $folder->parent));
    }

    public function create(User $user): bool {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, Folder $folder): bool {
        return $folder->storageConfig->user_id === $user->id || ($folder->parent && $user->can('update', $folder->parent));
    }

    public function delete(User $user, Folder $folder): bool {
        return $folder->storageConfig->user_id === $user->id || ($folder->parent && $user->can('update', $folder->parent));
    }

    public function restore(User $user, Folder $folder): bool {
        return $folder->storageConfig->user_id === $user->id || ($folder->parent && $user->can('update', $folder->parent));
    }

    public function forceDelete(User $user, Folder $folder): bool {
        return $folder->storageConfig->user_id === $user->id || ($folder->parent && $user->can('update', $folder->parent));
    }
}
