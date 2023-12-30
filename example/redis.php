<?php
namespace Example;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/boot.php";
require __DIR__ . "/Models/Data.php";
require __DIR__ . "/Models/RData.php";

// Redis ---------------------
use Example\Models\RData;

$rorm = getRedisOrm();

/**
 * @var RData $rres
 * @var RData $yyy
 */

// insert/update
$rres = new RData();
$rres->id = 2; // !!!
$rres->value = "" . rand(1, 100);
$rres->json = ["test" => md5("" . rand(1, 100)), "nest" => ["y" => "lo", "z" => true,]];
$rres->list = range(1, 10);
$rorm($rres)();

// get
$yyy = $rorm(RData::class)(2)();
print_r([
    "empty" => $rorm(RData::class)(1)(),
    "id2" => $yyy,
]);

$yyy->value = 100;
$rorm($yyy)();

print_r([
    "update" => $rorm(RData::class)(2)(),
]);

// // delete
$rorm(null)($yyy)();

print_r([
    "delete" => $rorm(RData::class)(2)(),
]);