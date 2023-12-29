<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;

class PDOUpsertQuery extends PDOQuery {
    private ModelInterface $_model;
    
    protected function run() {
        $model = $this->_model;
        $modelname = \strtolower(
            \basename(\str_replace('\\', '/', \get_class($model)))
        );

        $model->validate();

        $parts = [];
        $values = [];
        $marks = [];
        if($model->id) {
            foreach ($model as $k => $v) {
                $parts[] = "{$k} = ?";
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
                $values[] = $v;
            }
            $parts = \implode(",", $parts);
            $cmd = "update " . $this->getOrm()->getConfig()->getEntityPrefix() . "{$modelname} set {$parts} where id = {$model->id}";
            $this->getOrm()->getPDO()->prepare($cmd)->execute($values);
        } else {
            foreach ($model as $k => $v) {
                $parts[] = $k;
                if(\is_array($v)) {
                    if($v !== null) {
                        $v = \json_encode($v);
                        if ($v === false) {
                            throw new \Exception("JSON to DB error");
                        }
                    }
                }
                $values[] = $v;
                $marks[] = "?";
            }
            $parts = \implode(",", $parts);
            $marks = \implode(",", $marks);
            $cmd = "insert into " . $this->getOrm()->getConfig()->getEntityPrefix() . "{$modelname} ({$parts}) values ({$marks})";
            $this->getOrm()->getPDO()->prepare($cmd)->execute($values);
            $model->id = (int) $this->getOrm()->getPDO()->lastInsertId();
        }
    }

    protected function getMethods(): array {
        return [
            function(ModelInterface $model) { $this->_model = $model; },
        ];
    }
}