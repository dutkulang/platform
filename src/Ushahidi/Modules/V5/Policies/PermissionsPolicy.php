<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\Authorizer\PermissionAuthorizer;
use Ushahidi\Modules\V5\Models\Permissions as EloquentPermission;

class PermissionsPolicy
{
    protected $authorizer;

    public function __construct(PermissionAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    public function index(GenericUser $user):bool
    {
        $empty_permissions = new EloquentPermission();
        return $this->authorizer->setUser($user)->isAllowed($empty_permissions, 'search');
    }

    public function show(GenericUser $user, EloquentPermission $permissions):bool
    {
        return $this->authorizer->setUser($user)->isAllowed($permissions, 'read');
    }

    public function delete(GenericUser $user, EloquentPermission $permissions):bool
    {
        return $this->authorizer->setUser($user)->isAllowed($permissions, 'delete');
    }

    public function update(GenericUser $user, EloquentPermission $permissions):bool
    {
        return $this->authorizer->setUser($user)->isAllowed($permissions, 'update');
    }

    public function store(GenericUser $user):bool
    {
        $permissions = new EloquentPermission();
        return $this->authorizer->setUser($user)->isAllowed($permissions, 'create');
    }
}
