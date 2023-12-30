<?php declare(strict_types=1);

namespace Moteam\Orm\Query;
use Moteam\Orm\Contracts\ModelInterface;

class RabbitWriteQuery extends RabbitQuery {
    private ModelInterface $_model;

    protected function getMethods(): array {
        return [
            function(ModelInterface $model) { $this->_model = $model; },
        ];
    }

    protected function run() {
        $model = $this->_model;
        $model->validate();

        $prefix = $this->getOrm()->getConfig()->getEntityPrefix();
        $_tablename = \strtolower(
            \basename(\str_replace('\\', '/', \get_class($model)))
        );
        $_qn = $prefix . $_tablename;

        $channel = $this->getOrm()->getChannel();
        $channel->queue_declare($_qn, false, true, false, false);

        $data = [];
        foreach($model as $k => $v) {
            $data[$k] = $v;
        }

        $msg = new \PhpAmqpLib\Message\AMQPMessage(
            \json_encode($data),
            array('delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $channel->basic_publish($msg, '', $_qn);
    }
}