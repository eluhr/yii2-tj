<?php

namespace eluhr\tj;

use yii\db\ColumnSchema;
use yii\db\TableSchema;

/**
 * Class TableSchemaConverter
 *
 * @package eluhr\tj
 * @author Elias Luhr
 */
class TableSchemaConverter
{
    /**
     * Convert a given TableSchema to an array representation of a json schema. Either as array or as object
     *
     * @param TableSchema $tableSchema
     * @param bool $asArray
     *
     * @return array
     */
    public static function convert(TableSchema $tableSchema, bool $asArray = false): array
    {
        $jsonSchema = self::generateJsonSchema($tableSchema);

        if ($asArray) {
            return [
                'type' => 'array',
                'items' => $jsonSchema
            ];
        }
        return $jsonSchema;
    }

    /**
     * Generate the json schema array representation for the table schema
     *
     * @param TableSchema $tableSchema
     *
     * @return array
     */
    public static function generateJsonSchema(TableSchema $tableSchema): array
    {
        return [
            'type' => 'object',
            'properties' => static::generateProperties($tableSchema)
        ];
    }

    /**
     * Generate the properties array for the json schema
     *
     * @param TableSchema $tableSchema
     *
     * @return array
     */
    private static function generateProperties(TableSchema $tableSchema): array
    {
        $properties = [];
        foreach ($tableSchema->columns as $column) {
            // skip auto increment primary keys
            if ($column->isPrimaryKey && $column->autoIncrement) {
                continue;
            }

            $properties[$column->name] = static::generateProperty($column);
        }
        return $properties;
    }

    /**
     * Generate a single property for the json schema
     *
     * @param ColumnSchema $column
     *
     * @return array
     */
    private static function generateProperty(ColumnSchema $column): array
    {
        // Set default values for the property
        $property = [
            'type' => static::convertType($column),
            'required' => true
        ];

        // Set additional values for the property

        if ($column->defaultValue !== null || $column->allowNull) {
            $property['default'] = $column->allowNull ? null : $column->defaultValue;
        }

        if ($column->enumValues !== null) {
            $property['enum'] = $column->enumValues;
        }

        if ($column->size !== null) {
            $property['maxLength'] = $column->size;
        }

        // is unsigned and numeric, set minimum to 0
        if ($column->unsigned && in_array($column->phpType, ['integer', 'double'])) {
            $property['minimum'] = 0;
        }

        // is required, set minLength to 1 but only for string types
        if ($column->allowNull === false && $column->phpType === 'string') {
            $property['minLength'] = 1;
        }

        $format = static::generateFormat($column);
        if ($format !== null) {
            $property['format'] = $format;
        }


        return $property;
    }

    /**
     * Generate a format for the json schema
     *
     * @param ColumnSchema $column
     *
     * @return string|null
     */
    private static function generateFormat(ColumnSchema $column): ?string
    {

        // https://json-schema.org/understanding-json-schema/reference/string.html#built-in-formats
        if ($column->phpType === 'string') {
            if ($column->dbType === 'date') {
                return 'date';
            }

            $dateTimeTypes = ['datetime', 'timestamp'];
            if (in_array($column->dbType, $dateTimeTypes)) {
                return 'date-time';
            }

            if ($column->dbType === 'time') {
                return 'time';
            }
        }

        return null;
    }

    /**
     * Generate a json schema type from a given column type by its php type
     *
     * @param string $phpType
     *
     * @return string
     * @link https://json-schema.org/understanding-json-schema/reference/type.html
     *
     */
    private static function generateBaseType(string $phpType): string
    {
        if ($phpType === 'boolean') {
            return 'boolean';
        }

        if ($phpType === 'integer') {
            return 'integer';
        }

        if ($phpType === 'double') {
            return 'number';
        }

        // String is default because it represents all other types
        return 'string';
    }

    /**
     * Convert a column type to a json schema type
     *
     * @param ColumnSchema $column
     *
     * @return string|array
     */
    private static function convertType(ColumnSchema $column)
    {
        $baseType = static::generateBaseType($column->phpType);

        // add null type if column is nullable
        if ($column->allowNull) {
            return [$baseType, 'null'];
        }
        return $baseType;
    }
}
