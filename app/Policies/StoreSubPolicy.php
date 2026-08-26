<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StoreSub;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreSubPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StoreSub');
    }

    public function view(AuthUser $authUser, StoreSub $storeSub): bool
    {
        return $authUser->can('View:StoreSub');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StoreSub');
    }

    public function update(AuthUser $authUser, StoreSub $storeSub): bool
    {
        return $authUser->can('Update:StoreSub');
    }

    public function delete(AuthUser $authUser, StoreSub $storeSub): bool
    {
        return $authUser->can('Delete:StoreSub');
    }

    public function restore(AuthUser $authUser, StoreSub $storeSub): bool
    {
        return $authUser->can('Restore:StoreSub');
    }

    public function forceDelete(AuthUser $authUser, StoreSub $storeSub): bool
    {
        return $authUser->can('ForceDelete:StoreSub');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StoreSub');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StoreSub');
    }

    public function replicate(AuthUser $authUser, StoreSub $storeSub): bool
    {
        return $authUser->can('Replicate:StoreSub');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StoreSub');
    }

}