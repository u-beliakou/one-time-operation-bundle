<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Executor;

use Symfony\Component\HttpKernel\KernelInterface;
use Ubeliakou\OneTimeOperationSdk\Executor\Dto\ExecutionContext;

class ExecutionContextProvider
{
    private string $environment;
    private string $project;

    public function __construct(string $project, KernelInterface $kernel)
    {
        $this->environment = $kernel->getEnvironment();
        $this->project = $project;
    }

    public function getContext(): ExecutionContext
    {
        return new ExecutionContext($this->environment, $this->project);
    }
}