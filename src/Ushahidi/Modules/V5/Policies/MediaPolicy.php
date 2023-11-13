<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Tool\Authorizer\MediaAuthorizer;
use Ushahidi\Core\Ohanzee\Entity\Media as OhanzeeMedia;
use Ushahidi\Modules\V5\Models\Media as EloquentMedia;

class MediaPolicy
{
    protected $authorizer;

    public function __construct(AccessControl $acl, MediaAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    public function index(GenericUser $user)
    {
        $this->authorizer->setUser($user);

        $empty_media_entity = new OhanzeeMedia();
        return $this->authorizer->isAllowed($empty_media_entity, 'search');
    }

    public function show(GenericUser $user, EloquentMedia $media)
    {
        $this->authorizer->setUser($user);

        $media_entity = new OhanzeeMedia($media->toArray());
        return $this->authorizer->isAllowed($media_entity, 'read');
    }

    public function delete(GenericUser $user, EloquentMedia $media)
    {
        $this->authorizer->setUser($user);

        $media_entity = new OhanzeeMedia($media->toArray());
        return $this->authorizer->isAllowed($media_entity, 'delete');
    }

    public function update(GenericUser $user, EloquentMedia $media)
    {
        $this->authorizer->setUser($user);

        // we convert to a Media entity to be able to continue using the old authorizers and classes.
        $media_entity = new OhanzeeMedia($media->toArray());
        return $this->authorizer->isAllowed($media_entity, 'update');
    }

    public function store(GenericUser $user, EloquentMedia $media)
    {
        $this->authorizer->setUser($user);

        // we convert to a media_entity entity to be able to continue using the old authorizers and classes.
        $media_entity = new OhanzeeMedia($media->toArray());
        return $this->authorizer->isAllowed($media_entity, 'create');
    }
}
