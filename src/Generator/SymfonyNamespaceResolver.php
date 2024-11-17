<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Generator;

use Ubeliakou\OneTimeOperationSdk\Generator\NamespaceResolver\NamespaceResolver;

final class SymfonyNamespaceResolver extends NamespaceResolver
{
    public function getApplicationNamespace(): string
    {
        return 'App';
    }
}