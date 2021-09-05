<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Containers\DatabaseContainer;
use Astaroth\Foundation\FacadePlaceholder;
use Doctrine\ORM\EntityManagerInterface;

final class Entity
{
    /**
     * @return ?EntityManagerInterface
     */
    public static function manage(): ?object
    {
        return FacadePlaceholder::getContainer()->get(DatabaseContainer::CONTAINER_ID);
    }
}