<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Tool\Authorizer\UserAuthorizer;
use Ushahidi\Core\Ohanzee\Entity\User as OhanzeeUser;
use Ushahidi\Modules\V5\Models\User as EloquentUser;

class UserPolicy
{
    protected $authorizer;

    public function __construct(AccessControl $acl, UserAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer->setAcl($acl);
    }

    public function index(GenericUser $user): bool
    {
        $empty_model_user = new OhanzeeUser();
        return $this->authorizer->setUser($user)->isAllowed($empty_model_user, 'search');
    }

    public function show(GenericUser $user, EloquentUser $eloquentUser): bool
    {
        $entity = new OhanzeeUser($eloquentUser->toArray());
        return $this->authorizer->setUser($user)->isAllowed($entity, 'read');
    }

    public function register(GenericUser $user, EloquentUser $eloquentUser): bool
    {
        $entity = new OhanzeeUser();
        $entity->setState($eloquentUser->toArray());

        return $this->authorizer->setUser($user)->isAllowed($entity, 'register');
    }

    public function update(GenericUser $user, EloquentUser $eloquentUser): bool
    {
        $entity = new OhanzeeUser($eloquentUser->getRawOriginal());

        $entity->setState($eloquentUser->getDirty());


        return $this->authorizer->setUser($user)->isAllowed($entity, 'update');
    }

    public function store(GenericUser $user): bool
    {
        $entity = new OhanzeeUser();
        return $this->authorizer->setUser($user)->isAllowed($entity, 'create');
    }

    public function delete(GenericUser $user, EloquentUser $eloquentUser): bool
    {
        $entity = new OhanzeeUser();
        $entity->setState($eloquentUser->toArray());

        return $this->authorizer->setUser($user)->isAllowed($entity, 'delete');
    }
}
