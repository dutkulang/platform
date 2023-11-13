<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Tool\Authorizer\ApiKeyAuthorizer;
use Ushahidi\Modules\V5\Models\Apikey as EloquentApiKey;
use Ushahidi\Core\Ohanzee\Entity\ApiKey as OhanzeeApiKey;

class ApiKeyPolicy
{
    protected $authorizer;

    public function __construct(AccessControl $acl, ApiKeyAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer->setAcl($acl);
    }

    public function index(GenericUser $user)
    {
        $empty_apikey_entity = new OhanzeeApiKey();
        return $this->authorizer->setUser($user)->isAllowed($empty_apikey_entity, 'search');
    }

    public function show(GenericUser $user, EloquentApikey $apikey)
    {
        $apikey_entity = new OhanzeeApiKey($apikey->toArray());
        return $this->authorizer->setUser($user)->isAllowed($apikey_entity, 'read');
    }

    public function delete(GenericUser $user, EloquentApikey $apikey)
    {
        $apikey_entity = new OhanzeeApiKey($apikey->toArray());
        return $this->authorizer->setUser($user)->isAllowed($apikey_entity, 'delete');
    }

    public function update(GenericUser $user, EloquentApikey $apikey)
    {
        // we convert to a Apikey entity to be able to continue using the old authorizers and classes.
        $apikey_entity = new OhanzeeApiKey($apikey->toArray());
        return $this->authorizer->setUser($user)->isAllowed($apikey_entity, 'update');
    }

    public function store(GenericUser $user, EloquentApikey $apikey)
    {
        // we convert to a apikey_entity entity to be able to continue using the old authorizers and classes.
        $apikey_entity = new OhanzeeApiKey($apikey->toArray());
        return $this->authorizer->setUser($user)->isAllowed($apikey_entity, 'create');
    }
}
