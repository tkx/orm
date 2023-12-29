<?php
namespace Example;

use Moteam\Orm\Config;
use Moteam\Orm\QueryFacade\PdoCrudFacade;
use Moteam\Orm\PdoOrm;
use Example\Models\Data;

function getOrm(): PdoOrm {
    return (new PdoOrm((new Config())->setEntityPrefix("")->setModels([Data::class,])))
        ->connect(new \PDO("mysql:host=localhost;port=3306;dbname=test", "xxxusers", "xxxusers"))
        ->use(PdoCrudFacade::class);
}