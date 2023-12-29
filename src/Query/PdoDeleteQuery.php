<?php declare(strict_types=1);

namespace Moteam\Orm\Query;

class PdoDeleteQuery extends PDOQuery {
    private string $_modelname = "";
    private array $_where = [];
    private array $_values = [];

    protected function run() {
        $_modelname = \strtolower(\basename(\str_replace('\\', '/', $this->_modelname)));

        $_where = "1 = 1";
        $_values = [];
        if($this->_where) {
            $_where = implode(" and ", $this->_where);
            $_values = $this->_values;
        } else {
            $_values = [];
        }

        $cmd = "delete from " . $this->getOrm()->getConfig()->getEntityPrefix() . "{$_modelname} where {$_where}";
        $this->getOrm()->getPDO()->prepare($cmd)->execute($_values);
    }

    protected function getMethods(): array {
        return [
            function(string $modelname) { $this->_modelname = $modelname; },
            function(...$where) { $this->_where = $where; },
            function(...$values) { $this->_values = $values; },
        ];
    }
}