# PHP 7.4 ORM with fluent and concise query interface

## With PdoOrm and PdoCrudFacade

```php
$orm = (
        new PdoOrm(
            (new Config())
                // ->setEntityPrefix("") // table prefix (if needed)
                ->setModels([
                    DataModel::class, 
                    UserModel::class, 
                    ...
                ]) // models
        )
    )
    ->connect(new \PDO("mysql:host=localhost;port=3306;dbname=test", "test", "test"))
    ->use(PdoCrudFacade::class)

// select/fetch
// $orm(class)("where1", "where2")(value1, value2)(order)(limit/offset)();
$orm(UserModel::class) // QueryInterface returned
    ("id = ?", "created_at < ?") // add where
    (123, time() - 24 * 3600) // add values
    ("created_at desc") // add order
    ("0, 100") // add limit offset
    () // execute

// insert
// $orm(object)();
$user = new UserModel();
$user->created_at = time();
$orm($user)();

// delete
// $orm(null)("where1", "where2")(value1, value2)()
$orm(null) // create query
    ("created_at < ?") // add where
    (time() - 30 * 24 * 3600) // add values
    () // execute
```

## App level JSON fields
Json fields can be ruled by data schema, i.e. trying to save data that is not in schema will throw.
```php
class DataModel extends Model {
    // all fields need to be initialized
    public array $data = [];
    // equals `public $data = [];`
    public array $dummy = [];
    public int $x = 0;
    public string $y = "";
    public bool $x = false;

    public function getMeta(): array {
        return [
            "data" => [
                "value1" => "integer",
                "value2" => "string",
                "values" => [
                    "a" => "integer",
                    "b" => "string",
                    "c" => "array", // schema-less
                ]
            ],
            "dummy" => "array", // schema-less, i.e. can contain any json compatible data
        ]
    }
}
```

## Code first migrator (auto or on-demand)
```php
(new PdoMigrator())->migrate($orm);
```