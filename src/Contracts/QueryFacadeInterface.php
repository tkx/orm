<?php declare(strict_types=1);

namespace Moteam\Orm\Contracts;

/**
 * Receives input from ORM instance and creates queries.
 */
interface QueryFacadeInterface {
    /**
     * @return QueryInterface
     * Create concrete query by input
     */
    function __invoke(...$input): QueryInterface;
}