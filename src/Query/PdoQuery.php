<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\PdoOrm;

abstract class PDOQuery extends Query {
    protected PdoOrm $_orm;

    public function __construct(PdoOrm $orm) {
        $this->_orm = $orm;
    }

    protected function getOrm(): PdoOrm {
        return $this->_orm;
    }

    protected function fetchAll($cmd, $values=[]) {
        $s = $this->_orm->getPDO()->prepare($cmd);
        $s->execute($values);
        return $s->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function fetchRow($cmd, $values=[]) {
        $s = $this->_orm->getPDO()->prepare($cmd);
        $s->execute($values);
        return $s->fetch(\PDO::FETCH_ASSOC);
    }

    protected function fetchOne($cmd, $values=[]) {
        $s = $this->_orm->getPDO()->prepare($cmd);
        $s->execute($values);
        return $s->fetchColumn();
    }
}