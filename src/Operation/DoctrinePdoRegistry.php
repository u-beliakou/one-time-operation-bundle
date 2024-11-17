<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationBundle\Operation;

use Doctrine\DBAL\Connection;
use Exception;
use PDO;
use Ubeliakou\OneTimeOperationBundle\Operation\Exception\DbalDriverMismatchException;
use Ubeliakou\OneTimeOperationSdk\Registry\PdoRegistry;

class DoctrinePdoRegistry extends PdoRegistry
{
    /**
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct(string $tableName, Connection $connection)
    {
        $nativeConn = $connection->getNativeConnection();
        if (!$nativeConn instanceof PDO) {
            $actualType = is_object($nativeConn) ? get_class($nativeConn) : get_resource_type($nativeConn);

            throw new DbalDriverMismatchException(PDO::class, $actualType);
        }

        parent::__construct($tableName, $nativeConn);
    }
}