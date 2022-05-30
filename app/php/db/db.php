<?php

namespace db {
    $DB_PATH = '/var/db/app/db.json';
    $SCHEMA_PATH = '/var/www/html/assets/db/schema.json';
    $SCHEMA = json_decode(file_get_contents($SCHEMA_PATH), true);

    function read_db()
    {
        global $DB_PATH;
        return json_decode(file_get_contents($DB_PATH), true);
    }
    function write_db(array $db): void
    {
        global $DB_PATH;
        file_put_contents($DB_PATH, json_encode($db, JSON_UNESCAPED_UNICODE));
    }
    function init_db(): void
    {
        global $SCHEMA;
        $tables = array_keys($SCHEMA["tables"]);
        $db = array();
        foreach ($tables as $table) {
            $db[$table] = array();
        }
        write_db($db);
    }
    function is_db_valid(): bool
    {
        global $DB_PATH;
        return (read_db() !== null);
    }
    function has_table(string $table): bool
    {
        global $SCHEMA;
        return array_key_exists($table, $SCHEMA['tables']);
    }
    function get_fields(string $table): array
    {
        global $SCHEMA;
        assert(has_table($table));
        return $SCHEMA['tables'][$table]['fields'];
    }
    function has_field(string $table, string $field): bool
    {
        global $SCHEMA;
        assert(has_table($table));
        //  var_dump(get_fields($table));
        return in_array($field, get_fields($table));
    }
    function has_key(string $table): bool
    {
        global $SCHEMA;
        assert(has_table($table));
        return ($SCHEMA['tables'][$table]['params']['key'] !== null);
    }
    function get_key_name(string $table): string
    {
        global $SCHEMA;
        assert(has_key($table));
        return $SCHEMA['tables'][$table]["params"]["key"];
    }
    function create_empty_record(string $table): array
    {
        $record = array();
        foreach (get_fields($table) as $fieldName) {
            $record[$fieldName] = null;
        }
        return $record;
    }
    function get_table(string $table): array
    {
        assert(has_table($table));
        $db = read_db();
        return $db[$table];
    }
    function get_record(string $table, $key)
    {
        return get_table($table)[$key];
    }
    function get_records_by_field(string $table, string $field, $value): array
    {

        assert(has_field($table, $field));
        $result = array();
        foreach (get_table($table) as $key => $record) {
            if ($record[$field] === $value) {
                $result[$key] = $record;
            }
        }
        return $result;
    }
    function set_record(string $table, array $record): void
    {
        assert(has_table($table));
        assert(get_fields($table) === array_keys($record));
        $db = read_db();
        if (has_key($table)) {
            $db[$table][$record[get_key_name($table)]] = $record;
        }
        else {
            $db[$table][] = $record;
        }
        write_db($db);
    }
    function has_record_with_key(string $table, string $key): bool
    {
        return (get_record($table, $key) !== null);
    }

    function has_record(string $table, array $record): bool
    {
        foreach (get_table($table) as $key => $rec) {
            if ($record === $rec) {
                return true;
            }
        }
        return false;
    }

    if (!is_db_valid()) {
        init_db();
    }
}
