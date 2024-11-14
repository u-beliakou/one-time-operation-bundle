<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Tests\Mocked;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ubeliakou\OneTimeOperationBundle\Operation\DoctrineOperation;

final class FakeDoctrineOperation extends DoctrineOperation
{
    public function execute(): void
    {}

    public function getName(): string
    {
        return 'fake_doctrine_operation';
    }

    /**
     * @return MockObject|EntityManagerInterface
     */
    public function getEntityManager(): mixed
    {
        return $this->entityManager;
    }
}