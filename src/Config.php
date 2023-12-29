<?php declare(strict_types=1);

namespace Moteam\Orm;
use Moteam\Orm\Contracts\ConfigInterface;

class Config implements ConfigInterface {
    private array $_models;
    private string $_tablePrefix;

    public function __construct() {
        $this->_tablePrefix = "";
        $this->_models = [];
    }

    /** @inheritDoc */
    public function setModels(array $models): self {
        $this->_models = $models;
        return $this;
    }

    /** @inheritDoc */
    public function getModels(): array {
        return $this->_models;
    }

    /** @inheritDoc */
    public function setEntityPrefix(string $prefix): self {
        $this->_tablePrefix = $prefix;
        return $this;
    }

    /** @inheritDoc */
    public function getEntityPrefix(): string {
        return $this->_tablePrefix;
    }
}