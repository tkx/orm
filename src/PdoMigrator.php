<?php declare(strict_types=1);

namespace Moteam\Orm;
use Moteam\Orm\Contracts\ConfigInterface;
use Moteam\Orm\Contracts\MigratorInterface;
use Moteam\Orm\Contracts\OrmInterface;

class PdoMigrator implements MigratorInterface {
    /** @inheritDoc */
    public function migrate(OrmInterface $orm) {
        /** @var ConfigInterface $config */
        $config = $orm->getConfig();
        $modelnames = $config->getModels();
        /** @var \PDO $pdo */
        $pdo = $orm->getConnection();

        $q = $pdo->prepare("show tables");
        $q->execute();
        $res0 = $q->fetchAll();

        foreach($modelnames as $modelname) {
            $modelname = \strtolower($modelname);

            $created = false;
            foreach ($res0 as $r0) {
                if (strtolower($r0[0]) == strtolower($config->getEntityPrefix() . $modelname)) {
                    $created = true;
                    break;
                }
            }

            $cmd = null;

            if (!$created) {
                $model = new $modelname();
                $reflect = new \ReflectionClass($model);
                $props = $reflect->getProperties();

                $parts = [
                    "id int AUTO_INCREMENT PRIMARY KEY"
                ];

                foreach ($props as $prop) {
                    $name = $prop->getName();
                    if ($name == 'id')
                        continue;
                    $value = $model->$name;
                    switch (gettype($value)) {
                        case "integer":
                            $parts[] = "{$name} int null";
                            break;
                        case "string":
                            $parts[] = "{$name} varchar(255) null";
                            break;
                        case "array":
                            $parts[] = "{$name} text null";
                            break;
                        case "boolean":
                            $parts[] = "{$name} tinyint(1) null";
                            break;
                        default:
                            break;
                    }
                }

                $parts = implode(',', $parts);
                $cmd = "create table " . $config->getEntityPrefix() . "{$modelname} ( {$parts} )";
            } else {

                $q = $pdo->prepare("describe " . $config->getEntityPrefix() . "{$modelname}");
                $q->execute();
                $res = $q->fetchAll();
                $fields = [];
                foreach ($res as $r) {
                    $fields[] = $r[0];
                }

                $model = new $modelname();
                $reflect = new \ReflectionClass($model);
                $props = $reflect->getProperties();

                $parts = [];

                foreach ($props as $prop) {
                    $name = $prop->getName();
                    if ($name == 'id')
                        continue;

                    if (in_array($name, $fields))
                        continue;

                    $value = $model->$name;
                    switch (gettype($value)) {
                        case "integer":
                            $parts[] = "{$name} int null";
                            break;
                        case "string":
                            $parts[] = "{$name} varchar(255) null";
                            break;
                        case "array":
                            $parts[] = "{$name} text null";
                            break;
                        case "boolean":
                            $parts[] = "{$name} tinyint(1) null";
                            break;
                        default:
                            break;
                    }
                }

                if ($parts) {
                    $parts = implode(", add ", $parts);
                    $cmd = "alter table " . $config->getEntityPrefix() . "{$modelname} add {$parts}";
                }
            }

            if ($cmd) {
                $pdo->prepare($cmd)->execute();
            }
        }
    }
}