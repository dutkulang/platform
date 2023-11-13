<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser as User;
use Ushahidi\Core\Ohanzee\Entity\Set as OhanzeeSet;
use Ushahidi\Modules\V5\Models\Set as EloquentSet;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Tool\Authorizer\SetAuthorizer;

class SetPolicy
{
    protected $authorizer;

    public function __construct(AccessControl $acl, SetAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer;
        $this->authorizer->setAcl($acl);
    }

    public function viewAny(User $user)
    {
        $this->authorizer->setUser($user);
        return $this->authorizer->isAllowed(new OhanzeeSet, 'search');
    }

    public function view(User $user, EloquentSet $set)
    {
        $this->authorizer->setUser($user);

        $entity = new OhanzeeSet;
        $entity->setState($set->toArray());
        return $this->authorizer->isAllowed($entity, 'read');
    }

    public function store(User $user)
    {
        $this->authorizer->setUser($user);

        // we convert to a form entity to be able to continue using the old authorizers and classes.
        return $this->authorizer->isAllowed(new OhanzeeSet, 'create');
    }

    public function delete(User $user, EloquentSet $set)
    {
        $this->authorizer->setUser($user);

        $set_entity = new OhanzeeSet();
        $set_entity->setState($set->toArray());
        return $this->authorizer->isAllowed($set_entity, 'delete');
    }

    public function update(User $user, EloquentSet $set)
    {
        $this->authorizer->setUser($user);

        // we convert to a form entity to be able to continue using the old authorizers and classes.
        $set_entity = new OhanzeeSet($set->getRawOriginal());

        $set_entity->setState($set->getDirty());

        return $this->authorizer->isAllowed($set_entity, 'update');
    }
}
