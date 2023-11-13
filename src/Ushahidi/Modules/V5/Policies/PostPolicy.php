<?php

namespace Ushahidi\Modules\V5\Policies;

use Ushahidi\Core\Support\GenericUser;
use Ushahidi\Core\Tool\AccessControl;
use Ushahidi\Core\Tool\Authorizer\PostAuthorizer;
use Ushahidi\Core\Ohanzee\Entity\Post as OhanzeePost;
use Ushahidi\Modules\V5\Models\Post\Post as EloquentPost;

class PostPolicy
{
    protected $authorizer;

    public function __construct(AccessControl $acl, PostAuthorizer $authorizer)
    {
        $this->authorizer = $authorizer->setAcl($acl);
    }

    public function index(GenericUser $user)
    {
        $empty_post = new OhanzeePost();
        return $this->authorizer->setUser($user)->isAllowed($empty_post, 'search');
    }

    public function show(GenericUser $user, EloquentPost $post)
    {
        $post_entity = new OhanzeePost($this->getPostArray($post));
        return $this->authorizer->setUser($user)->isAllowed($post_entity, 'read');
    }

    public function update(GenericUser $user, EloquentPost $post)
    {
        $post_entity = new OhanzeePost($this->getPostArray($post));
        // we convert to a form entity to be able to continue using the old authorizers and classes.
        return $this->authorizer->setUser($user)->isAllowed($post_entity, 'update');
    }

    public function delete(GenericUser $user, EloquentPost $post)
    {
        $post_entity = new OhanzeePost($this->getPostArray($post));
        return $this->authorizer->setUser($user)->isAllowed($post_entity, 'delete');
    }

    public function patch(GenericUser $user, EloquentPost $post)
    {
        $post_entity = new OhanzeePost($this->getPostArray($post));
        // we convert to a form entity to be able to continue using the old authorizers and classes.
        return $this->authorizer->setUser($user)->isAllowed($post_entity, 'update');
    }

    public function changeStatus(GenericUser $user, EloquentPost $post)
    {
        $post_entity = new OhanzeePost($this->getPostArray($post));
        // we convert to a form entity to be able to continue using the old authorizers and classes.
        return $this->authorizer->setUser($user)->isAllowed($post_entity, 'update');
    }

    public function store(GenericUser $user, $form_id, $user_id)
    {
        // we convert to a form entity to be able to continue using the old authorizers and classes.
        $post_entity = new OhanzeePost(['form_id' => $form_id, 'user_id' => $user_id]);
        return $this->authorizer->setUser($user)->isAllowed($post_entity, 'create');
    }

    private function getPostArray($post)
    {
        $data = is_array($post) ? $post : $post->toArray();
        unset($data["completed_stages"]);
        unset($data["enabled_languages"]);
        unset($data["post_content"]);
        unset($data["translations"]);
        unset($data["sets"]);
        unset($data["categories"]);
        return $data;
    }
}
