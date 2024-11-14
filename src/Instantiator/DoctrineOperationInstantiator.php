<?php

namespace Ubeliakou\OneTimeOperationBundle\Instantiator;

use Doctrine\ORM\EntityManagerInterface;
use Ubeliakou\OneTimeOperationSdk\Inventory\Instantiator\OperationInstantiator;
use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;
use Ubeliakou\OneTimeOperationBundle\Operation\DoctrineOperation;

class DoctrineOperationInstantiator extends OperationInstantiator
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function configure(OperationInterface $operation): OperationInterface
    {
        if (method_exists($operation, 'setEntityManager')) {
            /** @var DoctrineOperation $operation */
            $operation->setEntityManager($this->entityManager);
        }

        return $operation;
    }

    protected function supports(OperationInterface $operation): bool
    {
        return $operation instanceof DoctrineOperation;
    }
}