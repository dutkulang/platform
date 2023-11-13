<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\Authorizer\RoleAuthorizer;
use Ushahidi\Core\Ohanzee\Entity\Role as OhanzeeRole;
use Ushahidi\Modules\V5\Models\Role as EloquentRole;

class RolePolicy
{
    protected $authorizer;

    public function __construct(RoleAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    public function index(GenericUser $user):bool
    {
        $empty_role = new OhanzeeRole();

        return $this->authorizer->setUser($user)->isAllowed($empty_role, 'search');
    }

    public function show(GenericUser $user, EloquentRole $role):bool
    {
        $staticRole = new OhanzeeRole($role->toArray());
        return $this->authorizer->setUser($user)->isAllowed($staticRole, 'read');
    }

    public function store(GenericUser $user, EloquentRole $role):bool
    {
        $staticRole = new OhanzeeRole($role->toArray());

        return $this->authorizer->setUser($user)->isAllowed($staticRole, 'create');
    }

    public function update(GenericUser $user, EloquentRole $role):bool
    {
        $staticRole = new OhanzeeRole($role->getRawOriginal());

        $staticRole->setState($role->getDirty());

        return $this->authorizer->setUser($user)->isAllowed($staticRole, 'update');
    }

    public function delete(GenericUser $user, EloquentRole $role):bool
    {
        $staticRole = new OhanzeeRole($role->toArray());

        return $this->authorizer->setUser($user)->isAllowed($staticRole, 'delete');
    }
}
