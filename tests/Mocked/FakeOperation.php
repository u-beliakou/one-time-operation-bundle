<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Tests\Mocked;

use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;

final class FakeOperation implements OperationInterface
{
    public function execute(): void
    {}

    public function getName(): string
    {
        return 'fake_operation';
    }
}