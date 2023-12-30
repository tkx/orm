<?php
namespace Example;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/boot.php";
require __DIR__ . "/Models/Data.php";
require __DIR__ . "/Models/RData.php";

// PDO --------------------
use Example\Models\Data;

$orm = getPdoOrm();

// save/insert
$res = new Data();
$res->value = rand(1, 100);
$res->json = ["test" => md5(rand(1, 100)), "nest" => ["y" => "lo", "z" => true]]; // array with schema
$res->list = ["a" => 1, 2, 3, 4, 5]; // any array
$orm($res)();

// select
/** @var Data $xxx */
print_r([
    "value" => $res->value,
    "total" => count($orm(Data::class)()),
    "1" => $orm(Data::class)("id = ?")(1)(),
    "same" => $xxx = $orm(Data::class)("value = ?", "id = ?")($res->value, $res->id)("rand()")(0, 1)(),
    "max" => $orm(Data::class)("id >= ?")(1)("value desc")(0, 1)(),
    "json" => $xxx->json["nest"]["z"],
]);

// delete
$orm(null)(Data::class)("id = ?")(1)();
// $orm(false)($res)();

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
