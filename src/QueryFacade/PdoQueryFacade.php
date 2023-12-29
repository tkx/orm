<?php declare(strict_types=1);

namespace Moteam\Orm\QueryFacade;
use Moteam\Orm\Contracts\QueryFacadeInterface;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\PdoOrm;

abstract class PdoQueryFacade implements QueryFacadeInterface {
    protected PdoOrm $_orm;

    public function __construct(PdoOrm $orm) {
        $this->_orm = $orm;
    }

    public function getOrm(): PdoOrm {
        return $this->_orm;
    }

    /**
     * @inheritDoc
     */
    abstract public function __invoke(...$input): QueryInterface;
}