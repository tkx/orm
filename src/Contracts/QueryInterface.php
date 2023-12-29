<?php declare(strict_types=1);

namespace Moteam\Orm\Contracts;

interface QueryInterface {
    /** -------------------------------
     * @return self|mixed|array<ModelInterface>|ModelInterface
     * Fluent query interface.
     * Returns array of models or just one model
     */
    function __invoke(...$args);
}