<?php

namespace Ushahidi\Modules\V5\Actions\SavedSearch\Commands;

use App\Bus\Command\Command;
use Ushahidi\Core\Data\SetEntity;

class UpdateSavedSearchCommand implements Command
{
    /**
     * @var SetEntity
     */
    private $entity;

    /**
     * @var int
     */
    private $id;


    public function __construct(int $id, SetEntity $entity)
    {
        $this->entity = $entity;
        $this->id = $id;
    }

    public function getEntity(): SetEntity
    {
        return $this->entity;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
