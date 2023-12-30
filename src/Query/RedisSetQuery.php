<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;

class RedisSetQuery extends RedisQuery {
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

        $model->validate();

        $pairs = [];
        
        foreach ($model as $k => $v) {
            if(\is_array($v)) {
                if($v) {
                    $v = \json_encode($v);
                    if ($v === false) {
                        throw new \Exception("JSON to DB error");
                    }
                } else {
                    $v = "[]";
                }
            }
            $pairs["{$prefix}{$_tablename}_{$model->id}_{$k}"] = $v;
        }
        
        $this->getOrm()->getRedis()->mSet($pairs);
    }
}