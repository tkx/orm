<?php declare(strict_types=1);

namespace Moteam\Orm;
use Moteam\Orm\Contracts\ModelInterface;

/**
 * Model implementing the following fields:
 * - integer
 * - string
 * - boolean
 * - list (non-associative array)
 * - map (associative array)
 */
abstract class Model implements ModelInterface {
    public int $id = 0;

    /** @inheritDoc */
    public function load() {}

    public function __get($k) {
        throw new \InvalidArgumentException("Invalid property: '{$k}'");
    }

    public function __set($k, $v) {
        throw new \InvalidArgumentException("Invalid property: '{$k}'");
    }
    
    /** @inheritDoc */
    public function validate() {
        $validate_json = function(array $path, array $json, array $meta) use (&$validate_json): bool {
            $valid = true;
            foreach($json as $k => $v) {
                if(!\array_key_exists($k, $meta)) {
                    throw new \InvalidArgumentException("Not is schema: " . \get_class($this) . "::" . \implode("::", $path) . "::" . $k);
                }

                if(\is_array($v) && \is_array($meta[$k])) {
                    $valid = $validate_json($path + [$k],$v, $meta[$k]);
                } else {
                    $valid = $valid && \gettype($v) == $meta[$k];
                }
            }
            return $valid;
        };

        $valid = true;
        $meta = $this->getMeta();

        foreach($meta as $name => $_m) {
            if(\is_array($this->{$name}) && \is_array($_m)) {
                $valid = $valid && $validate_json([$name], $this->{$name}, $_m);
            } else {
                $valid = $valid && \gettype($this->{$name}) == $_m;
            }
            if(!$valid) {
                throw new \InvalidArgumentException("Not is schema: " . \get_class($this) . "::" . $name);
            }
        }
    }

    /** @inheritDoc */
    abstract public static function getMeta(): array;
}