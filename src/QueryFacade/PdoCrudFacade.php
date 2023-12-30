<?php declare(strict_types=1);

namespace Moteam\Orm\QueryFacade;
use Moteam\Orm\Contracts\QueryFacadeInterface;
use Moteam\Orm\Contracts\ModelInterface;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\Query\PdoSelectQuery;
use Moteam\Orm\Query\PdoUpsertQuery;
use Moteam\Orm\Query\PdoDeleteQuery;
use Moteam\Orm\PdoOrm;

/**
 * PDO backed select, insert, update, delete queries
 * 
 * select:
 * $orm(Model::class)("x = ?", "y < ?")(1, "xxx")("date desc")("0, 100")()
 * 
 * insert/update:
 * $model = new Model();
 * $model->x = 1;
 * $model->y = "data";
 * $orm($model)();
 * 
 * delete:
 * $orm(null)(Model::class)("id = ?")(1)();
 */
class PdoCrudFacade implements QueryFacadeInterface {
    protected PdoOrm $_orm;

    public function __construct(PdoOrm $orm) {
        $this->_orm = $orm;
    }

    public function getOrm(): PdoOrm {
        return $this->_orm;
    }

    public function __invoke(...$input_all): QueryInterface {
        list($input) = $input_all;
        
        // classname passed -> select
        if(\is_string($input)) {
            return (new PdoSelectQuery($this->getOrm()))($input);
        } 
        else if($input instanceof ModelInterface) { // model passed -> save
            return (new PdoUpsertQuery($this->getOrm()))($input);
        } 
        else if($input === null) { // null passed -> delete
            return new PdoDeleteQuery($this->getOrm());
        }
        
        throw new \InvalidArgumentException();
    }
}