<?php

namespace eluhr\tj\interfaces;

use yii\db\TableSchema;

/**
 * Class BaseTableSchemaConverter
 *
 * @package eluhr\tj
 *
 * @author Elias Luhr
 */
interface TableSchemaConverterInterface
{
    /**
     * Convert a given TableSchema to an array representation of a json schema.
     *
     * @param TableSchema $tableSchema
     *
     * @return array
     */
    public static function convert(TableSchema $tableSchema): array;
}
