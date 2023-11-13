<?php

namespace Ushahidi\Modules\V5\Actions\SavedSearch\Commands;

use App\Bus\Command\Command;
use Ushahidi\Core\Data\SetEntity;

class CreateSavedSearchCommand implements Command
{
    private $entity;

    public function __construct(SetEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return SetEntity
     */
    public function getEntity(): SetEntity
    {
        return $this->entity;
    }
}
