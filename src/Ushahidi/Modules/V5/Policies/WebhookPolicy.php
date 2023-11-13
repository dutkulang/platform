<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\Authorizer\WebhookAuthorizer;
use Ushahidi\Core\Ohanzee\Entity\Webhook as OhanzeeWebhook;
use Ushahidi\Modules\V5\Models\Webhook\Webhook as EloquentWebhook;

class WebhookPolicy
{
    protected $authorizer;

    public function __construct(WebhookAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    public function index(GenericUser $user)
    {
        $empty_webhook_entity = new OhanzeeWebhook();
        return $this->authorizer->setUser($user)->isAllowed($empty_webhook_entity, 'search');
    }

    public function show(GenericUser $user, EloquentWebhook $webhook)
    {
        $webhook_entity = new OhanzeeWebhook($webhook->toArray());
        return $this->authorizer->setUser($user)->isAllowed($webhook_entity, 'read');
    }

    public function store(GenericUser $user, EloquentWebhook $webhook)
    {
        // we convert to a webhook_entity entity to be able to continue using the old authorizers and classes.
        $webhook_entity = new OhanzeeWebhook($webhook->toArray());
        return $this->authorizer->setUser($user)->isAllowed($webhook_entity, 'create');
    }

    public function update(GenericUser $user, EloquentWebhook $webhook)
    {
        // we convert to a Webhook entity to be able to continue using the old authorizers and classes.
        $webhook_entity = new OhanzeeWebhook($webhook->toArray());
        return $this->authorizer->setUser($user)->isAllowed($webhook_entity, 'update');
    }

    public function delete(GenericUser $user, EloquentWebhook $webhook)
    {
        $webhook_entity = new OhanzeeWebhook($webhook->toArray());
        return $this->authorizer->setUser($user)->isAllowed($webhook_entity, 'delete');
    }
}
