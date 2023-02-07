# Table to JSON Schema

A helper to convert a yii\db\TableSchema to a valid JSON Schema php array representation.

## How to use

```php
use eluhr\tj\ObjectTableSchemaConverter;
use eluhr\tj\ArrayTableSchemaConverter;

$tableSchema = Yii::$app->getDb()->getTableSchema('{{%user}}');

$jsonSchemaAsObject = ObjectTableSchemaConverter::convert($tableSchema);
$jsonSchemaAsArray = ArrayTableSchemaConverter::convert($tableSchema);

return $jsonSchema;
```
