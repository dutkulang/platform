<?php

namespace Ushahidi\Modules\V5\Actions\Collection\Commands;

use App\Bus\Command\Command;
use Ushahidi\Core\Data\SetEntity;

class UpdateCollectionCommand implements Command
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


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
