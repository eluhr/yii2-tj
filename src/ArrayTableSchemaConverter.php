<?php

namespace eluhr\tj;

use yii\db\TableSchema;

/**
 * Class ArrayTableSchemaConverter
 *
 * @package eluhr\tj
 * @author Elias Luhr
 */
class ArrayTableSchemaConverter extends BaseTableSchemaConverter
{
    /**
     * @inheritdoc
     */
    public static function convert(TableSchema $tableSchema): array
    {
        return [
            'type' => 'array',
            'items' => self::generateJsonSchema($tableSchema)
        ];
    }
}
