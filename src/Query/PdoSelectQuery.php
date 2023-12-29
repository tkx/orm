<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;

class PDOSelectQuery extends PDOQuery {
    private string $_modelname = "";
    private array $_where = [];
    private array $_values = [];
    private string $_order_by = "";
    private array $_limit = [];

    protected function getMethods(): array {
        return [
            function(string $modelname) { $this->_modelname = $modelname; },
            function(...$where) { $this->_where = $where; },
            function(...$values) { $this->_values = $values; },
            function(string $order_by) { $this->_order_by = $order_by; },
            function(...$limit) { $this->_limit = $limit; },
        ];
    }

    protected function run() {
        $_tablename = \strtolower(\basename(\str_replace('\\', '/', $this->_modelname)));
        $_modelname = $this->_modelname;

        $_where = "1 = 1";
        $_values = [];
        if($this->_where) {
            $_where = \implode(" and ", $this->_where);
            $_values = $this->_values;
        } else {
            $_values = [];
        }

        $_order_by = "";
        if($this->_order_by){
            $_order_by = "order by " . $this->_order_by;
        }

        $_limit = "";
        if($this->_limit){
            $_limit = "limit " . $this->_limit[0] . (\count($this->_limit) > 1 ? (", " . $this->_limit[1]) : "");
        }

        $cmd = "select * from " . $this->getOrm()->getConfig()->getEntityPrefix() . "{$_tablename} where {$_where} {$_order_by} {$_limit}";
        $res = $this->fetchAll($cmd, \array_values($_values));

        // print_r($res);

        $models = [];
        if(!$res) {
            return null;
        }

        foreach($res as $r) {
            /** @var ModelInterface $model */
            $model = new $_modelname();

            foreach ($r as $k => $v) {
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
            $models[] = $model;
        }

        return count($models) > 1 ? $models : $models[0];
    }
}
