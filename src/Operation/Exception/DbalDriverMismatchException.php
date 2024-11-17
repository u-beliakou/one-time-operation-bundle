<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Operation\Exception;

use Exception;

class DbalDriverMismatchException extends Exception
{
    public function __construct(string $expectedDriver, string $actualDriver)
    {
        $message = sprintf(
            'Invalid registry configuration. Expected %s, got %s',
            $expectedDriver,
            $actualDriver
        );

        parent::__construct($message);
    }
}