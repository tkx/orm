<?php declare(strict_types=1);

namespace Moteam\Orm\QueryFacade;
use Moteam\Orm\Contracts\QueryFacadeInterface;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\Query\RabbitReadQuery;
use Moteam\Orm\Query\RabbitWriteQuery;
use Moteam\Orm\RabbitOrm;

class RabbitReadWriteFacade implements QueryFacadeInterface {
    protected RabbitOrm $_orm;

    public function __construct(RabbitOrm $orm) {
        $this->_orm = $orm;
    }

    public function getOrm(): RabbitOrm {
        return $this->_orm;
    }

    public function __invoke(...$input_all): QueryInterface {
        list($input) = $input_all;
        
        // classname passed -> select
        if(\is_string($input)) {
            return (new RabbitReadQuery($this->getOrm()))($input);
        } 
        else if($input instanceof ModelInterface) { // model passed -> save
            return (new RabbitWriteQuery($this->getOrm()))($input);
        }
        
        throw new \InvalidArgumentException();
    }
}