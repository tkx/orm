<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;
use Moteam\Orm\Contracts\OrmInterface;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\PdoOrm;

abstract class PDOQuery implements QueryInterface {
    protected PdoOrm $_orm;
    protected int $_state = 0;

    /**
     * @return array<ModelInterface>|ModelInterface|null
     */
    abstract protected function run();
    /**
     * @return array<\Closure>
     */
    abstract protected function getMethods(): array;

    public function __construct(PdoOrm $orm) {
        $this->_orm = $orm;
    }

    protected function getOrm(): PdoOrm {
        return $this->_orm;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(...$args) {
        if(\count($args)) {
            $method = $this->getMethods()[$this->_state++];
            \call_user_func($method, ...$args);

            return $this;
        }

        return $this->run();
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