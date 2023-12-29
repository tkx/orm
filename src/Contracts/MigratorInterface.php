<?php declare(strict_types=1);

namespace Moteam\Orm\Contracts;

interface MigratorInterface {
    /**
     * @param OrmInterface $orm
     */
    function migrate(OrmInterface $orm);
}