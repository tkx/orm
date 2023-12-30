<?php declare(strict_types=1);

namespace Moteam\Orm;

class RedisOrm extends Orm {
    /** @inheritDoc */
    public function getConnection() {
        return $this->getRedis();
    }

    public function getRedis(): \Redis {
        return $this->_connection;
    }
}