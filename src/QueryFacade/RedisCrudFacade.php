<?php declare(strict_types=1);

namespace Moteam\Orm\QueryFacade;
use Moteam\Orm\Contracts\ModelInterface;
use Moteam\Orm\Contracts\QueryFacadeInterface;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\Query\RedisDelQuery;
use Moteam\Orm\Query\RedisGetQuery;
use Moteam\Orm\Query\RedisSetQuery;
use Moteam\Orm\RedisOrm;

/**
 * Redis backed get, set, del
 * 
 * $orm($obj)()
 * $orm(null)($obj)()
 * $orm(Model::class)(id)()
 */
class RedisCrudFacade implements QueryFacadeInterface {
    protected RedisOrm $_orm;

    public function __construct(RedisOrm $orm) {
        $this->_orm = $orm;
    }

    public function getOrm(): RedisOrm {
        return $this->_orm;
    }

    public function __invoke(...$input_all): QueryInterface {
        list($input) = $input_all;
        
        // classname passed -> select
        if(\is_string($input)) {
            return (new RedisGetQuery($this->getOrm()))($input);
        } 
        else if($input instanceof ModelInterface) { // model passed -> save
            return (new RedisSetQuery($this->getOrm()))($input);
        } 
        else if($input === null) { // null passed -> delete
            return new RedisDelQuery($this->getOrm());
        }
        
        throw new \InvalidArgumentException();
    }
}