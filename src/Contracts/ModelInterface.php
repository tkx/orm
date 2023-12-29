<?php declare(strict_types=1);

namespace Moteam\Orm\Contracts;

/**
 * @property int $id
 */
interface ModelInterface {
    /** ------------------------------
     * Perform custom actions after row fetched.
     */
    function load();
    /** ------------------------------
     * Perform custom actions before saving.
     */
    function validate();
    /** --------------------------------
     * @return array<string, string>
     */
    static function getMeta(): array;
}