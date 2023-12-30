<?php declare(strict_types=1);

namespace Moteam\Orm;

class RedisOrm extends Orm {
    /** @inheritDoc */
    public function getConnection() {
        return $this->getRedis();
    }

    /**
     * @return \Redis|\Predis\Client
     * Any Redis connection with standard commands interface
     */
    public function getRedis() {
        return $this->_connection;
    }
}