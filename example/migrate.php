<?php
namespace Example;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/boot.php";
require __DIR__ . "/Models/Data.php";

use Moteam\Orm\PdoMigrator;

$orm = getOrm();

(new PdoMigrator())->migrate($orm);