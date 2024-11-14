<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Tests\Instantiator;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationBundle\Instantiator\DoctrineOperationInstantiator;
use Ubeliakou\OneTimeOperationBundle\Tests\Mocked\FakeDoctrineOperation;
use Ubeliakou\OneTimeOperationBundle\Tests\Mocked\FakeOperation;
use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryException;

class DoctrineOperationInstantiatorTest extends TestCase
{
    private MockObject $mockedEm;
    private DoctrineOperationInstantiator $sut;

    protected function setUp(): void
    {
        $this->mockedEm = $this->createMock(EntityManagerInterface::class);
        $this->sut = new DoctrineOperationInstantiator($this->mockedEm);
    }

    public function testInstantiateThrowsExceptionOnOperationIsNotSupported(): void
    {
        $this->expectException(InventoryException::class);
        $this->expectExceptionCode(InventoryException::OPERATION_NOT_SUPPORTED);

        $this->sut->instantiate(FakeOperation::class);
    }

    public function testInstantiateHappyPath(): void
    {
        $operation = $this->sut->instantiate(FakeDoctrineOperation::class);

        $this->assertInstanceOf(FakeDoctrineOperation::class, $operation);
        $this->assertSame($this->mockedEm, $operation->getEntityManager());
    }

}