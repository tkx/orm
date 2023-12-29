<?php
namespace Example\Models;
use Moteam\Orm\Model;

class Data extends Model {
    public string $value = "";
    public array $json = [];
    public array $list = [];

    public static function getMeta(): array {
        return [
            "json" => [
                "test" => "string",
                "nest" => [
                    "x" => "integer",
                    "y" => "string",
                    "z" => "boolean",
                ],
            ],
        ];
    }
}