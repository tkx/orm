<?php declare(strict_types=1);

namespace Moteam\Orm\Contracts;

interface ConfigInterface {
    /** ---------------------------------
     * @param string $prefix
     */
    public function setEntityPrefix(string $prefix): self;
    /** ----------------------------------
     * @return string
     */
    public function getEntityPrefix(): string;
    /** ---------------------------------
     * @param array<string> $models
     */
    function setModels(array $models): self;
    /** -----------------------------------------
     * @return array<string>
     */
    function getModels(): array;
}