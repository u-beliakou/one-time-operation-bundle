<?php

namespace Ubeliakou\OneTimeOperationBundle\Generator;

use Ubeliakou\OneTimeOperationSdk\Generator\Template\OperationTemplateInterface;

final class DoctrineOperationTemplate implements OperationTemplateInterface
{
    public function compile(string $namespace, string $className, string $operationName): string
    {
        return "<?php

namespace $namespace;

use Ubeliakou\OneTimeOperationBundle\Operation\DoctrineOperation;

final class $className extends DoctrineOperation
{
    public function getName(): string
    {
        return '$operationName';
    }

    public function execute(): void
    {
        // TODO: Implement the operation logic here.
    }
}
";
    }
}