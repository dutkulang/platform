<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Tool\Authorizer\ContactAuthorizer;
use Ushahidi\Core\Ohanzee\Entity\Contact as OhanzeeContact;
use Ushahidi\Modules\V5\Models\Contact as EloquentContact;

class ContactPolicy
{
    protected $authorizer;

    public function __construct(ContactAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    public function index(GenericUser $user)
    {
        $empty_contact_entity = new OhanzeeContact();
        return $this->authorizer->setUser($user)->isAllowed($empty_contact_entity, 'search');
    }

    public function show(GenericUser $user, EloquentContact $contact)
    {
        $contact_entity = new OhanzeeContact($contact->toArray());
        return $this->authorizer->setUser($user)->isAllowed($contact_entity, 'read');
    }

    public function store(GenericUser $user, EloquentContact $contact)
    {
        // we convert to a contact_entity entity to be able to continue using the old authorizers and classes.
        $contact_entity = new OhanzeeContact($contact->toArray());
        return $this->authorizer->setUser($user)->isAllowed($contact_entity, 'create');
    }

    public function update(GenericUser $user, EloquentContact $contact)
    {
        // we convert to a ContactEntity entity to be able to continue using the old authorizers and classes.
        $contact_entity = new OhanzeeContact($contact->getRawOriginal());

        $contact_entity->setState($contact->getDirty());

        return $this->authorizer->setUser($user)->isAllowed($contact_entity, 'update');
    }

    public function delete(GenericUser $user, EloquentContact $contact)
    {
        $contact_entity = new OhanzeeContact($contact->toArray());
        return $this->authorizer->setUser($user)->isAllowed($contact_entity, 'delete');
    }
}
