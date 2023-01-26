<?php

namespace eluhr\tj;

use yii\db\TableSchema;

/**
 * Class ObjectTableSchemaConverter
 *
 * @package eluhr\tj
 * @author Elias Luhr
 */
class ObjectTableSchemaConverter extends BaseTableSchemaConverter
{
    /**
     * @inheritdoc
     */
    public static function convert(TableSchema $tableSchema): array
    {
        return self::generateJsonSchema($tableSchema);
    }
}
