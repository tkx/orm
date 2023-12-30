<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;
use Moteam\Orm\Contracts\OrmInterface;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\RedisOrm;

abstract class RedisQuery extends Query {
    protected RedisOrm $_orm;

    public function __construct(RedisOrm $orm) {
        $this->_orm = $orm;
    }

    protected function getOrm(): RedisOrm {
        return $this->_orm;
    }
}