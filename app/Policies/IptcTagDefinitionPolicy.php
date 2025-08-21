<?php

namespace App\Policies;

use App\Models\IptcTagDefinition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IptcTagDefinitionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function view(User $user, IptcTagDefinition $iptcTagDefinition): bool
    {
        return $iptcTagDefinition->user_id === $user->id || $iptcTagDefinition->user_id === null;
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, IptcTagDefinition $iptcTagDefinition): bool
    {
        return $iptcTagDefinition->user_id === $user->id;
    }

    public function delete(User $user, IptcTagDefinition $iptcTagDefinition): bool
    {
        return $iptcTagDefinition->user_id === $user->id;
    }

    public function restore(User $user, IptcTagDefinition $iptcTagDefinition): bool
    {
        return $iptcTagDefinition->user_id === $user->id;
    }

    public function forceDelete(User $user, IptcTagDefinition $iptcTagDefinition): bool
    {
        return $iptcTagDefinition->user_id === $user->id;
    }
}
