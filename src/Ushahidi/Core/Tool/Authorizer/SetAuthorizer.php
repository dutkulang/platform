<?php

/**
 * Ushahidi Set Authorizer
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Tool\Authorizer;

use Ushahidi\Contracts\Entity;
use Ushahidi\Contracts\Authorizer;
use Ushahidi\Core\Data\PermissionEntity as Permission;
use Ushahidi\Core\Concerns\AccessPrivileges;
use Ushahidi\Core\Concerns\AdminAccess;
use Ushahidi\Core\Concerns\OwnerAccess;
use Ushahidi\Core\Concerns\UserContext;
use Ushahidi\Core\Concerns\PrivateDeployment;
use Ushahidi\Core\Concerns\ControlAccess;

// The `SetAuthorizer` class is responsible for access checks on `Sets`
class SetAuthorizer implements Authorizer
{
    // The access checks are run under the context of a specific user
    use UserContext;

    // It uses methods from several traits to check access:
    // - `OwnerAccess` to check if a user owns the set
    // - `AdminAccess` to check if the user has admin access
    use AdminAccess, OwnerAccess;

    // It uses `AccessPrivileges` to provide the `getAllowedPrivs` method.
    use AccessPrivileges;

    // It uses `PrivateDeployment` to check whether a deployment is private
    use PrivateDeployment;

    // Check that the user has the necessary permissions
    // if roles are available for this deployment.
    use ControlAccess;

    /**
     * @param \Ushahidi\Core\Data\SetEntity $set
     * @param \Ushahidi\Core\Data\UserEntity $user
     * @return boolean
     */
    protected function isVisibleToUser(Entity $set, $user)
    {
        if ($set->role) {
            return in_array($user->role, $set->role);
        }

        // If no roles are selected, the Set is considered completely public.
        return true;
    }

    /* Authorizer */
    public function isAllowed(Entity $set, $privilege)
    {
        // Firstly, all users can search sets
        if ($privilege === 'search') {
            return true;
        }

        // These checks are run within the user context.
        $user = $this->getUser();

        // Only logged in users have access if the deployment is private
        if (!$this->canAccessDeployment($user)) {
            return false;
        }

        // We check if a user has the 'admin' role. If they do they're
        // allowed access to everything (all entities and all privileges)
        $is_admin = $this->isUserAdmin($user);
        if ($is_admin) {
            return true;
        }

        // We check whether there is a role with the right permissions
        if ($this->acl->hasPermission($user, Permission::MANAGE_SETS)) {
            return true;
        }

        // Non-admin users are not allowed to make sets featured
        if (!$is_admin && $set->hasChanged('featured') && in_array($privilege, ['create', 'update'])) {
            return false;
        }

        $isUserOwner = $this->isUserOwner($set, $user);
        // If the user is the owner of this set, they can do anything
        if ($isUserOwner) {
            return true;
        }

        // TODO: We want to check if the set entity is available only to owner
        // if (!$isUserOwner && $entity->view_options['only_me'] == true) {
        //     return false;
        // }

        // Check if the Set is only visible to specific roles.
        if ($this->isVisibleToUser($set, $user) and $privilege === 'read') {
            return true;
        }

        // All *logged in* users can create sets
        if ($user->getId() and $privilege === 'create') {
            return true;
        }

        // If no other access checks succeed, we default to denying access
        return false;
    }
}
