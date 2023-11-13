<?php

/**
 * Repository for Config Entity
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Platform
 * @copyright  2022 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Data;

use Ushahidi\Contracts\Repository\ReadRepository;
use Ushahidi\Contracts\Repository\UpdateRepository;
use Ushahidi\Contracts\Repository\DeleteRepository;

/**
 * @method array groups()
 * @method array all(array $groups = null)
 *
 */
interface ConfigRepository extends ReadRepository, UpdateRepository, DeleteRepository
{

}
