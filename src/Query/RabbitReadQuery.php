<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;

class RabbitReadQuery extends RabbitQuery {
    private string $_modelname = "";
    
    protected function getMethods(): array {
        return [
            function(string $modelname) { $this->_modelname = $modelname; },
        ];
    }

    protected function run() {
        $prefix = $this->getOrm()->getConfig()->getEntityPrefix();
        $_tablename = \strtolower(\basename(\str_replace('\\', '/', $this->_modelname)));
        $_modelname = $this->_modelname;
        $_qn = $prefix . $_tablename;

        $channel = $this->getOrm()->getChannel();
        $channel->queue_declare($_qn, false, true, false, false);

        $res = null;

        $callback = function($response) use(&$res) {
            $res = \json_decode($response->getBody(), true);
            $response->ack();
        };

        $channel->basic_consume($_qn, '', false, false, false, false, $callback);

        while(!$res) {
            $channel->wait();
        }

        // create model
        /** @var ModelInterface $model */
        $model = new $_modelname();
        foreach ($res as $k => $v) {
            $type = \gettype($model->{$k});
            if($type == "array") {
                try {
                    $model->$k = $v;
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