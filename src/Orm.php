<?php declare(strict_types=1);

namespace Moteam\Orm;
use Moteam\Orm\Contracts\OrmInterface;
use Moteam\Orm\Contracts\ConfigInterface;
use Moteam\Orm\Contracts\QueryInterface;
use Moteam\Orm\Contracts\QueryFacadeInterface;

abstract class Orm implements OrmInterface {
    /**
     * @property ConfigInterface $_config
     * Orm itself config: used models, etc
     */
    protected ConfigInterface $_config;
    /**
     * 
     */
    protected QueryFacadeInterface $_facade;
    /**
     * @property mixed $_connection
     * PDO, Redis, etc connection
     */
    protected $_connection;

    public function __construct(ConfigInterface $config) {
        $this->_config = $config;
        $this->_connection = null;
    }

    /** @inheritDoc */
    public function getConfig(): ConfigInterface {
        return $this->_config;
    }

    /** 
     * @inheritDoc
     */
    public function connect($connection): self {
        if($this->_connection) {
            return $this;
        }

        $this->_connection = $connection;
        return $this;
    }

    /** @inheritDoc */
    public function getConnection() {
        return $this->_connection;
    }

    public function use(string $facadeClass): self {
        $this->_facade = new $facadeClass($this);
        return $this;
    }
    public function getQueryFacade(): QueryFacadeInterface {
        return $this->_facade;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(...$input): QueryInterface {
        return $this->getQueryFacade()(...$input);
    }
}