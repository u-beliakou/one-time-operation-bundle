<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Operation;

use Doctrine\ORM\EntityManagerInterface;
use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;

abstract class DoctrineOperation implements OperationInterface
{
    protected EntityManagerInterface $entityManager;

    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->entityManager = $em;
    }
}