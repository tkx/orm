<?php declare(strict_types=1);

namespace Moteam\Orm\Query;

class RedisGetQuery extends RedisQuery {
    private string $_modelname = "";
    private int $_id = 0;
    
    protected function getMethods(): array {
        return [
            function(string $modelname) { $this->_modelname = $modelname; },
            function(int $id) { $this->_id = $id; },
        ];
    }

    protected function run() {
        $prefix = $this->getOrm()->getConfig()->getEntityPrefix();

        $_tablename = \strtolower(\basename(\str_replace('\\', '/', $this->_modelname)));
        $_modelname = $this->_modelname;

        $model = new $_modelname();
        $keys = [];
        foreach($model as $k => $v) {
            $keys[] = "{$prefix}{$_tablename}_{$this->_id}_{$k}";
        }

        $res = $this->getOrm()->getRedis()->mGet($keys);
        if(!$res[0]) { // id is empty -> no record
            return null;
        }

        $i = 0;
        foreach($model as $k => $_) {
            /** @var string $v */
            $v = $res[$i++];
            if(!$v) {
                continue;
            }

            $type = \gettype($model->{$k});
            if($type == "array") {
                try {
                    $model->$k = \json_decode($v ?: "[]", true);
                } catch(\Error $e) {
                    $model->$k = [];
                }
            } else if($type == "integer") {
                $model->$k = (int)$v;
            } else if($type == "boolean") {
                $model->$k = (bool)$v;
            } else {
                $model->$k = $v;
            }
        }

        $model->load();

        return $model;
    }
}