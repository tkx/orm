<?php declare(strict_types=1);

namespace Moteam\Orm\Contracts;

/**
 * @method __invoke(...$input)
 * Calls query facade with $input for query.
 * $orm(anything or nothing) => QueryFacade => Query
 */
interface OrmInterface {
    /** --------------------------------
     * @return ConfigInterface
     */
    function getConfig(): ConfigInterface;
    
    /**
     * @return QueryFacadeInterface
     */
    function getQueryFacade(): QueryFacadeInterface;
    /**
     * @param string $facadeClass
     * Set query facade class to use
     */
    function use(string $facadeClass): self;
    
    /** --------------------------------
     * @return mixed
     */
    function getConnection();
    
    /** --------------------------------
     * @param mixed $connection
     * @return OrmInterface
     */
    function connect($connection): self;

    /** ----------------------------------
     * @param string|ModelInterface $input
     * @return QueryInterface
     * One method for all types of queries.
     * Implementations decide which QueryInterface depending on input.
     */
    function __invoke(...$input): QueryInterface;
}