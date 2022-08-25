<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\JsonApi\V1\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use InetStudio\ACL\Users\Contracts\Models\UserModelContract;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;

class GroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(?UserModelContract $user)
    {
        return true;
    }

    public function view(?UserModelContract $user, GroupModelContract $group)
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

    public function delete(?UserModelContract $user, GroupModelContract $group)
    {
        return true;
    }

    public function viewEntries(?UserModelContract $user, GroupModelContract $group)
    {
        return true;
    }

    public function updateEntries(?UserModelContract $user, GroupModelContract $group)
    {
        return true;
    }

    public function attachEntries(?UserModelContract $user, GroupModelContract $group)
    {
        return true;
    }

    public function detachEntries(?UserModelContract $user, GroupModelContract $group)
    {
        return true;
    }
}
