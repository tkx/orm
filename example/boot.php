<?php
namespace Example;

use Moteam\Orm\Config;
use Moteam\Orm\QueryFacade\PdoCrudFacade;
use Moteam\Orm\QueryFacade\RedisCrudFacade;
use Moteam\Orm\PdoOrm;
use Example\Models\Data;
use Example\Models\RData;
use Moteam\Orm\RedisOrm;

function getPdoOrm(): PdoOrm {
    return (new PdoOrm((new Config())->setEntityPrefix("")->setModels([Data::class,])))
        ->connect(new \PDO("mysql:host=localhost;port=3306;dbname=test", "xxxusers", "xxxusers"))
        ->use(PdoCrudFacade::class);
}

function getRedisOrm(): RedisOrm {
    $redis = new \Redis();
    $redis->connect('localhost', 6379);

    return (new RedisOrm((new Config())->setEntityPrefix("")->setModels([RData::class,])))
        ->connect($redis)
        ->use(RedisCrudFacade::class);
}