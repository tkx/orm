<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\RabbitOrm;

abstract class RabbitQuery extends Query {
    protected RabbitOrm $_orm;

    public function __construct(RabbitOrm $orm) {
        $this->_orm = $orm;
    }

    protected function getOrm(): RabbitOrm {
        return $this->_orm;
    }
}