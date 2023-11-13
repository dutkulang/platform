<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Tool\Authorizer\ConfigAuthorizer;
use Ushahidi\Core\Ohanzee\Entity\Config as OhanzeeConfig;
use Ushahidi\Modules\V5\Models\Config as EloquentConfig;

class ConfigPolicy
{
    protected $authorizer;

    public function __construct(AccessControl $acl, ConfigAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer->setAcl($acl);
    }

    public function index(GenericUser $user)
    {
        $empty_config_entity = new OhanzeeConfig();
        return $this->authorizer->setUser($user)->isAllowed($empty_config_entity, 'search');
    }

    public function show(GenericUser $user, EloquentConfig $config)
    {
        $config_entity = new OhanzeeConfig($config->toArray());
        return $this->authorizer->setUser($user)->isAllowed($config_entity, 'read');
    }

    public function store(GenericUser $user, EloquentConfig $config)
    {
        // we convert to a config_entity entity to be able to continue using the old authorizers and classes.
        $config_entity = new OhanzeeConfig($config->toArray());
        return $this->authorizer->setUser($user)->isAllowed($config_entity, 'create');
    }

    public function update(GenericUser $user, EloquentConfig $config)
    {
        // we convert to a ConfigEntity entity to be able to continue using the old authorizers and classes.
        $config_entity = new OhanzeeConfig($config->getRawOriginal());
        $config_entity->setState($config->getDirty());

        return $this->authorizer->setUser($user)->isAllowed($config_entity, 'update');
    }

    public function delete(GenericUser $user, EloquentConfig $config)
    {
        $config_entity = new OhanzeeConfig($config->toArray());
        return $this->authorizer->setUser($user)->isAllowed($config_entity, 'delete');
    }
}
