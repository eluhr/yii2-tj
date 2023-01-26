# Table to JSON Schema

So here is the plan: I want to write a helper or something like that to convert a yii\db\TableSchema to a valid JSON Schema php array representation.

## How to use

```php
use eluhr\tj\TableSchemaConverter;

$tableSchema = Yii::$app->getDb()->getTableSchema('{{%user}}');
$jsonSchema = TableSchemaConverter::convert($tableSchema);

return $jsonSchema;
```
