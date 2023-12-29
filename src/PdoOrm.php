<?php declare(strict_types=1);

namespace Moteam\Orm;

/**
 * PDO backed ORM.
 * @property \PDO $_connection
 */
class PdoOrm extends Orm {
    /** @inheritDoc */
    public function getConnection() {
        return $this->getPDO();
    }

    public function getPDO(): \PDO {
        return $this->_connection;
    }
}