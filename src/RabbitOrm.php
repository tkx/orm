<?php declare(strict_types=1);

namespace Moteam\Orm;

class RabbitOrm extends Orm {
    /** @var \PhpAmqpLib\Channel\AMQPChannel */
    protected $_channel;

    /** @inheritDoc */
    public function getConnection() {
        return $this->getRabbit();
    }

    /**
     * @return \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    public function getRabbit() {
        return $this->_connection;
    }

    public function getChannel() {
        return $this->_channel;
    }

    public function connect($connection): self {
        parent::connect($connection);

        $this->_channel = $this->getConnection()->channel();

        return $this;
    }

    public function __destruct() {
        $this->_channel->close();
    }
}