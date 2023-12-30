<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;

class RedisDelQuery extends RedisQuery {
    private ModelInterface $_model;

    protected function getMethods(): array {
        return [
            function(ModelInterface $model) { $this->_model = $model; },
        ];
    }

    protected function run() {
        $prefix = $this->getOrm()->getConfig()->getEntityPrefix();
        $model = $this->_model;
        $_tablename = \strtolower(
            \basename(\str_replace('\\', '/', \get_class($model)))
        );
        
        foreach ($model as $k => $v) {
            $this->getOrm()->getRedis()->del("{$prefix}{$_tablename}_{$model->id}_{$k}");
        }
    }
}