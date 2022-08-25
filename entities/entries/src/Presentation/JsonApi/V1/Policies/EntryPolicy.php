<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use InetStudio\ACL\Users\Contracts\Models\UserModelContract;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;

class EntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(?UserModelContract $user)
    {
        return true;
    }

    public function view(?UserModelContract $user, EntryModelContract $entry)
    {
        return true;
    }

    public function create(?UserModelContract $user)
    {
        return true;
    }

    public function update(?UserModelContract $user)
    {
        return true;
    }

    public function delete(?UserModelContract $user, EntryModelContract $entry)
    {
        return true;
    }

    public function viewGroups(?UserModelContract $user, EntryModelContract $group)
    {
        return true;
    }

    public function updateGroups(?UserModelContract $user, EntryModelContract $group)
    {
        return true;
    }

    public function attachGroups(?UserModelContract $user, EntryModelContract $group)
    {
        return true;
    }

    public function detachGroups(?UserModelContract $user, EntryModelContract $group)
    {
        return true;
    }
}
