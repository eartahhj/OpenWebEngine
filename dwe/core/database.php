<?php

abstract class DatabaseModel
{
    private static $connectionString = '';
    protected static $type = '';
    public static $connection = null;
    public static $host = '';
    public static $user = '';
    public static $password = '';
    public static $name = '';

    public static function connect()
    {
        if (!static::$connection) {
            throw new ErrorException('Could not connect to Database');
            return '';
        }

        return static::$connection;
    }

    public static function queryUpdate(string $table, array $fields, string $where): bool
    {
        global $_messages;

        $query = '';
        $i = 1;
        static::transactionStart();
        $query .= "UPDATE $table SET ";
        foreach ($fields as $name => $value) {
            $query .= ($i > 1 ? ',' : '') . $name . "=" . $value;
            $i++;
        }
        $query.=' WHERE ' . $where . '; ';
        if (static::query($query)) {
            static::transactionCommit();
            $_messages->add(ALERT_TYPE_DEBUG, 'Update query executed: '.$query);
            return true;
        } else {
            $error = static::lastError();
            static::transactionRollback();
            $_messages->add(ALERT_TYPE_ERROR, 'Error updating data.');
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, $error);
            return false;
        }
    }

    public static function queryInsert(string $table, array $fields, string $returningColumnName = '', string $primaryKeyColumnName = ''): int
    {
        global $_messages;

        static::transactionStart();
        $query = '';
        $insertFields = $insertValues = '';

        $i = 0;
        foreach ($fields as $field => $value) {
            $i++;
            $insertFields .= ($i > 1 ? ',' : '') . $field;
            $insertValues .= ($i > 1 ? ',' : '') . $value;
        }

        $query .= "INSERT INTO {$table}({$insertFields}) VALUES({$insertValues})";

        if ($returningColumnName and static::$type == 'pg') {
            $query .= " RETURNING $returningColumnName";
        }

        if ($result = static::query($query)) {
            static::transactionCommit();
            $_messages->add(ALERT_TYPE_DEBUG, 'Insert query executed: ' . $query);

            if ($returningColumnName and static::$type == 'my') {
                # On MariaDB, RETURNING is supported only since 10.5.0 https://mariadb.com/kb/en/insertreturning/
                $query = "SELECT {$returningColumnName} FROM $table ORDER BY {$returningColumnName} DESC LIMIT 1";
                if (!$result = static::query($query)) {
                    $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, 'Error selecting last inserted record');
                }

                $returningValue = static::fetch($result);
                return ($returningValue->{$returningColumnName} ?? false);
            }

            return true;
        } else {
            $message = static::lastError();
            static::transactionRollback();
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, $message);
            return false;
        }
    }

    public static function queryDelete(string $tableName, string $primaryKeyColumnName, $primaryKeyValue): bool
    {
        global $_messages;

        $query="DELETE FROM $tableName WHERE $primaryKeyColumnName = $primaryKeyValue";
        static::transactionStart();
        if ($delete = static::query($query)) {
            static::transactionCommit();
            $_messages->add(ALERT_TYPE_DEBUG, 'Query di eliminazione eseguita: ' . $query);
            return true;
        } else {
            static::transactionRollback();
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, $messaggio);
            return false;
        }
    }

    public static function disconnect()
    {
        if (static::$type == 'pg') {
            pg_close(static::$connection);
        }
    }

    public static function transactionStart()
    {
        if (static::$type == 'pg') {
            static::query("BEGIN;");
        }

        if (static::$type=='my') {
            static::query("BEGIN;");
        }
    }

    public static function transactionRollback()
    {
        if (static::$type == 'pg') {
            static::query("ROLLBACK;");
        }

        if (static::$type=='my') {
            static::query("ROLLBACK;");
        }
    }

    public static function transactionCommit()
    {
        if (static::$type == 'pg') {
            static::query("COMMIT;");
        }

        if (static::$type=='my') {
            static::query("COMMIT;");
        }
    }

    public static function getNextSequenceIdFromTable(string $table): ?int
    {
        if (($sequence = static::getCurrentSequenceIdFromTable($table)) !== null) {
            return $sequence + 1;
        }
    }

    public static function getCurrentSequenceIdFromTable(string $table): ?int
    {
        global $_messages;

        if (static::$type == 'pg') {
            $query = "SELECT currval() AS id FROM $table";
        } elseif (static::$type == 'my') {
            $tableSchema = static::$name;
            $query = "SELECT AUTO_INCREMENT AS id FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$tableSchema}' AND TABLE_NAME = '$table'";
        }

        if (!$result = static::query($query)) {
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, 'Error selecting last sequence value');
            return null;
        } else {
            if (!$record = static::fetch($result)) {
                return null;
            } else {
                return $record->id;
            }
        }
    }
}

class DatabaseTableColumn
{
    public const COLUMN_TYPE_TEXT = 'TEXT';
    public const COLUMN_TYPE_INTEGER = 'INTEGER';
    public const COLUMN_TYPE_DATE = 'DATE';
    public const COLUMN_TYPE_TIMESTAMP = 'TIMESTAMP';

    protected $name = '';
    protected $type = '';
    protected $order = 1;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order)
    {
        $this->order = $order;
    }
}

class DatabaseTableStructure
{
    protected $tableName = '';
    protected $columns = [];

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function addColumn(DatabaseTableColumn $column)
    {
        while (isset($this->columns[$column->getOrder()])) {
            $column->setOrder($column->getOrder() + 1);
        }

        $this->columns[$column->getOrder()] = clone $column;
        unset($column);
    }

    public function getCreateTableQuery(): string
    {
        $query = '';

        if (empty($this->columns)) {
            throw new LogicException('No columns defined for this Database Table');
        } else {
            $query = "CREATE TABLE $this->tableName IF NOT EXISTS";

            foreach ($this->columns as $column) {
            }
        }

        return $query;
    }
}

class DatabaseMySQL extends DatabaseModel
{
    protected static $type = 'my';

    public static function connect()
    {
        static::$connection = new Mysqli(static::$host, static::$user, static::$password, static::$name);
        static::$connection->set_charset("utf8mb4");

        return parent::connect();
    }

    public static function query(string $query)
    {
        return static::$connection->query($query);
    }

    public static function fetch($result)
    {
        return $result->fetch_object();
    }

    public static function numRows(&$result): int
    {
        $rows = 0;

        return $rows;
    }

    public static function freeResult(&$result)
    {
        return $result->free();
    }

    public static function escapeString(string $data)
    {
        return static::$connection->real_escape_string($data);
    }

    public static function escapeLiteral(string $data)
    {
        return "'" . static::$connection->real_escape_string($data) . "'";
    }

    public static function lastError()
    {
        return '';
    }
}

class DatabasePostgreSQL extends DatabaseModel
{
    public static $user = '';
    public static $password = '';
    public static $name = '';

    public function __construct()
    {
        static::$connectionString = '';
    }

    public static function connect()
    {
        static::$connection = pg_connect(static::$connectionString);

        return parent::connect();
    }

    public static function query(string $query)
    {
        return pg_query(static::$connection, $query);
    }

    public static function fetch($result)
    {
        return pg_fetch_object($result);
    }

    public static function fetchRow($result, $row=0)
    {
        return pg_fetch_row($result, $row);
    }

    public static function numRows(&$result): int
    {
        $rows = pg_num_rows($result);

        if ($rows == -1) {
            throw new Exception('Error while calculating number of rows');
        }

        return $rows;
    }

    public static function freeResult(&$result)
    {
        return pg_free_result($result);
    }

    public static function lastError()
    {
        return pg_last_error(static::$connection);
    }

    public static function escapeString(string $data)
    {
        return pg_escape_string(static::$connection, $data);
    }

    public static function escapeLiteral(string $data)
    {
        return pg_escape_literal(static::$connection, $data);
    }

    public static function affectedRows($result)
    {
        return pg_affected_rows($result);
    }
}

class Database extends DatabaseMySQL
{
    public static $user = 'db_user';
    public static $password = 'db_password';
    public static $name = 'db_name';
}

Database::connect();
