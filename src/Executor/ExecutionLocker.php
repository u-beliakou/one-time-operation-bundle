<?php

namespace Ubeliakou\OneTimeOperationBundle\Executor;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Symfony\Component\Lock\Store\FlockStore;

class ExecutionLocker
{
    private const RESOURCE_NAME = 'one_time_operation_execute';

    private LockInterface $lock;

    public function __construct()
    {
        $store = new FlockStore(sys_get_temp_dir());
        $factory = new LockFactory($store);

        $this->lock = $factory->createLock(self::RESOURCE_NAME);
    }

    public function acquire(): void
    {
        $this->lock->acquire(true);
    }

    public function release(): void
    {
        $this->lock->release();
    }
}