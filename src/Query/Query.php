<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\Contracts\ModelInterface;

abstract class Query implements QueryInterface {
    protected int $_state = 0;

    /**
     * @return array<ModelInterface>|ModelInterface|null
     */
    abstract protected function run();
    /**
     * @return array<\Closure>
     */
    abstract protected function getMethods(): array;

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
}