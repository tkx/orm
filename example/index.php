<?php
namespace Example;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/boot.php";
require __DIR__ . "/Models/Data.php";

use Example\Models\Data;

$orm = getOrm();

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