<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\Authorizer\CSVAuthorizer;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Ohanzee\Entity\CSV as OhanzeeCSV;
use Ushahidi\Modules\V5\Models\CSV as EloquentCSV;

class CSVPolicy
{
    protected $authorizer;

    public function __construct(AccessControl $acl, CSVAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer->setAcl($acl);
    }

    public function index(GenericUser $user)
    {
        $empty_csv_entity = new OhanzeeCSV();
        return $this->authorizer->setUser($user)->isAllowed($empty_csv_entity, 'search');
    }

    public function show(GenericUser $user, EloquentCSV $csv)
    {
        $csv_entity = new OhanzeeCSV($csv->toArray());
        return $this->authorizer->setUser($user)->isAllowed($csv_entity, 'read');
    }

    public function store(GenericUser $user, EloquentCSV $csv)
    {
        // we convert to a csv_entity entity to be able to continue using the old authorizers and classes.
        $csv_entity = new OhanzeeCSV($csv->toArray());
        return $this->authorizer->setUser($user)->isAllowed($csv_entity, 'create');
    }

    public function update(GenericUser $user, EloquentCSV $csv)
    {
        // we convert to a CSV entity to be able to continue using the old authorizers and classes.
        $csv_entity = new OhanzeeCSV($csv->getRawOriginal());

        $csv_entity->setState($csv->getDirty());

        return $this->authorizer->setUser($user)->isAllowed($csv_entity, 'update');
    }

    public function delete(GenericUser $user, EloquentCSV $csv)
    {
        $csv_entity = new OhanzeeCSV($csv->toArray());
        return $this->authorizer->setUser($user)->isAllowed($csv_entity, 'delete');
    }
}
