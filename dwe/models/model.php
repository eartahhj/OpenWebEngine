<?php

require_once $_SERVER['APP_ROOT'] . 'core/database.php';

abstract class BaseModelMultilanguage
{
    protected $id = 0;
    protected $title = '';

    abstract public function renderHtml(): string;

    public function __construct(int $id=0)
    {
        $this->id = $id;
    }

    final public function getId(): int
    {
        return $this->id;
    }

    final public function setId(int $id): void
    {
        $this->id = $id;
        return;
    }

    final public function setTitle(string $title): void
    {
        $this->title = $title;
        return;
    }

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function returnDefaultTitle()
    {
        global $_language;
        if ($this->getPropertyMultilang('title', $_language)) {
            return $this->getPropertyMultilang('title', $_language);
        } else {
            return $this->title ?? '';
        }
    }

    final public function getProperty(string $propertyName)
    {
        return $this->{$propertyName} ?? '';
    }

    final public function setPropertyMultilang(string $propertyName, $value): bool
    {
        global $_languages;

        foreach ($_languages as $languageTag => $languageName) {
            if (!isset($this->{$propertyName . '_' . $languageTag})) {
                throw new InvalidArgumentException(_('The requested property [' . $propertyName . '_' . $languageTag . '] to set is not configured in the class: ' . __CLASS__));
                return false;
            }
            $this->{$propertyName . '_' . $languageTag} = $value;
        }

        return true;
    }

    final public function setPropertyMultilangFromObject(string $propertyName, $object)
    {
        global $_languages;

        foreach ($_languages as $languageTag => $languageName) {
            if (!isset($object->{$propertyName . '_' . $languageTag})) {
                throw new LogicException(_('Trying to set a property [' . $propertyName . '_' . $languageTag . '] for the class ' . __CLASS__ . ' but the given object does not contain that property'));
                return false;
            }
            $this->setPropertyMultilang($propertyName, $object->{$propertyName . '_' . $languageTag});
        }

        return true;
    }

    final public function getPropertyMultilang(string $propertyName, string $language='')
    {
        global $_language;

        if (!$language) {
            $language = $_language;
        }

        $language = strtolower($language);

        return $this->{$propertyName.'_'.$language} ?? '';
    }
}

abstract class BaseModelMultilanguageNoDb extends BaseModelMultilanguage
{
    protected $order=0;

    final public function getOrder(): int
    {
        return $this->order;
    }

    final public function setOrder(int $order): void
    {
        $this->order=$order;
        return;
    }
}

abstract class DbTable
{
    protected $dbTable = '';
    protected $primaryKey = '';
    protected $columnNameForOptionsList = '';
    protected $indexColumn = '';
    protected $recordClassToCreate = '';
    protected $where = '';
    protected $orderBy = '';
    protected $limit = 0;
    protected $offset = '';
    protected $records = [];
    protected $list = [];
    protected $optionsList = [];

    final public function setRecords(array $records)
    {
        $this->records = $records;
    }


    public function setRecordsByColumns(array $columns = [], string $where = '')
    {
        $this->records = $this->getRecordsByColumns($columns, $where);

        if ($this->records) {
            return true;
        }

        return false;
    }

    public function getRecordsByQuery(string $query, string $indexColumn = ''): array
    {
        global $_db, $_lingua;

        $records = [];

        if (!$result = Database::query($query)) {
            throw new ErrorException('Error loading the requested data');
            return '';
        } else {
            while ($record = Database::fetch($result)) {
                if ($indexColumn) {
                    $records[$record->{$indexColumn}] = $record;
                } else {
                    $records[] = $record;
                }
            }
            return $records;
        }
    }

    public function setRecordsByQuery(string $query, string $indexColumn = '')
    {
        return $this->getRecordsByQuery($query, $indexColumn);
    }

    final public function setWhere(string $where)
    {
        $this->where = $where;
    }

    final public function getLimit(): int
    {
        return $this->limit;
    }

    final public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    final public function getOffset(): string
    {
        return $this->offset;
    }

    final public function setOffset(string $offset)
    {
        $this->offset = $offset;
    }

    final public function addWhereCondition(string $where, string $logicOperator = '')
    {
        if ($this->where) {
            $this->where .= ' ';
            if ($logicOperator) {
                $this->where .= $logicOperator . ' ';
            }
            $this->where .= $where;
        }
    }

    final public function getWhere(): string
    {
        return $this->where;
    }

    final public function setOrderBy(string $orderBy)
    {
        $this->orderBy=$orderBy;
    }

    final public function getRecords(): array
    {
        return $this->records;
    }

    final public function getList(): array
    {
        return $this->list;
    }

    final public function getCompleteList(): array
    {
        return $this->getList();
    }

    final public function getOptionsList()
    {
        return $this->optionsList;
    }

    final public function getColumnNameForOptionsList(): string
    {
        return $this->columnNameForOptionsList;
    }

    final public function getIndexColumn(): string
    {
        return $this->indexColumn;
    }

    public function prepareOptionsList()
    {
        if (!$this->records) {
            return false;
        } else {
            try {
                foreach ($this->records as $indexColumn => $record) {
                    if (!isset($record->{$this->columnNameForOptionsList})) {
                        throw new LogicException('Could not find a column with that name for this table');
                    }
                    $object = new $this->recordClassToCreate();
                    $object->setDataByObject($record);
                    $this->optionsList[$indexColumn] = $record->{$this->columnNameForOptionsList};
                    unset($object);
                }
            } catch (Exception $error) {
                echo $error->getMessage();
                return false;
            }
        }
    }

    final public function countRecords(string $where = '')
    {
        $query = "SELECT COUNT(*) AS total FROM $this->dbTable";

        if ($where) {
            $query .= " WHERE {$where}";
        }

        if (!$result = Database::query($query)) {
            return new ErrorException('Error retrieving records');
        } else {
            if ($record = Database::fetch($result)) {
                return (int)$record->total;
            }
        }
    }

    final public function countRecordsByCondition(string $where)
    {
        return $this->countRecords($where);
    }

    final public function getMaximumFieldValue(string $field, string $where = '')
    {
        $query = "SELECT MAX($field) AS max FROM $this->dbTable";

        if ($where) {
            $query .= " WHERE {$where}";
        }

        if (!$result = Database::query($query)) {
            return new ErrorException('Error retrieving records');
        } else {
            if ($record = Database::fetch($result)) {
                return (int)$record->max;
            }
        }
    }

    final public function getRecordsByColumns(array $columns = [], string $where = ''): array
    {
        $records = [];

        $query = "SELECT ";
        if (!$columns) {
            $query .= '*';
        } else {
            $query .= implode(',', $columns);
        }
        $query .= " FROM {$this->dbTable}";
        $query .= ($where ? ' WHERE ' . $where : '');


        if ($this->orderBy) {
            $query .= ' ORDER BY ' . $this->orderBy;
        }

        if ($this->limit) {
            $query .= ' LIMIT ' . (int)$this->limit;
        }

        if (!$result = Database::query($query)) {
            return new ErrorException('Error retrieving records');
        } else {
            while ($record = Database::fetch($result)) {
                if ($this->indexColumn) {
                    $records[$record->{$this->indexColumn}] = $record;
                } elseif (isset($record->id)) {
                    $records[$record->id] = $record;
                } else {
                    $records[] = $record;
                }
            }
        }

        return $records;
    }

    final public function getRecordsByOneColumn(string $column, string $where = ''): array
    {
        return $this->getRecordsByColumns([$column], $where);
    }

    final public function getOneResultById(int $id)
    {
        if (!$this->list) {
            $this->prepareList();
        }
        return $this->list[$id] ?? '';
    }

    final public function getRecordsByColumnValue(string $column, $value): array
    {
        $where = $column . ' = ' . $value;
        return $this->getRecordsByColumns([$column], $where);
    }

    public function prepareList()
    {
        $this->list = [];

        if (!$this->records) {
            return false;
        } else {
            foreach ($this->records as $id => $record) {
                $object = new $this->recordClassToCreate();
                $object->setDataByObject($record);
                $this->list[$id] = clone $object;
                unset($object);
            }
            return true;
        }
    }

    final public function hasRecords(): bool
    {
        return count($this->records) ? true : false;
    }

    final public function getFirstResult()
    {
        if (!$this->list) {
            $this->prepareList();
        }
        return reset($this->list);
    }

    public function getAllRecords(string $where = ''): array
    {
        return $this->getRecordsByColumns([], $where);
    }

    public function renderHtml()
    {
        $html='';
        if ($this->records) {
            $html.="<ul>\n";
            foreach ($this->list as $id=>$element) {
                $html.='<li>';
                if ($element->getHtml()) {
                    $html.=$element->getHtml();
                } else {
                    $html.=$element->useDefaultHtmlRendering();
                }
                $html.="</li>\n";
            }
            $html.="</ul>\n";
        }
        return $html;
    }

    public function insertRecord(array $fields): bool
    {
        return Database::queryInsert($this->dbTable, $fields);
    }
}

abstract class DbMultiTable extends DbTable
{
}

abstract class DbTableRecord extends BaseModelMultilanguage
{
    protected $dbTable='';
    protected $id = 0;
    protected $primaryKeyColumnName = '';
    protected $primaryKeyValue = null;
    protected $record;
    protected $dateCreation='';
    protected $dateModified='';
    protected $title='';
    protected $userCreator = '';
    protected $userLastEdit = '';
    protected static $classNameReadable = '';

    public function __construct(int $id = 0)
    {
        $this->id=$id;
    }

    public function getDbTable(): string
    {
        return $this->dbTable;
    }


    public function setDataByObject($object)
    {
        $this->record = $object;

        $this->id = $object->id ?? 0;

        $this->primaryKeyValue = $object->{$this->primaryKeyColumnName} ?? null;

        if (isset($object->title)) {
            $this->title = $object->title;
        }


        if (isset($object->date_created)) {
            $this->dateCreation = substr($object->date_created, 0, 19);
        }

        if (isset($object->date_modified)) {
            $this->dateModified = ($object->date_modified ? substr($object->date_modified, 0, 19) : '');
        }

        if (isset($object->admin_creator)) {
            $this->userCreator = (int)$object->admin_creator;
        }

        if (isset($object->admin_last_editor)) {
            $this->userLastEdit = (int)$object->admin_last_editor;
        }

        return;
    }

    public function getRecordByCondition(string $whereCondition, array $columns = [])
    {
        if (!$this->dbTable) {
            throw new ErrorException('Database Table not set');
            return null;
        }

        global $_db;

        $record = null;
        $query = "SELECT ";

        if (!$columns) {
            $query .= '*';
        } else {
            $query .= implode(',', $columns);
        }

        $query .= " FROM $this->dbTable WHERE ";
        $query .= $whereCondition;

        if (!$result = Database::query($query)) {
            throw new ErrorException('Error searching for the requested data with query: ' . $query);
        } else {
            $record = Database::fetch($result);
        }

        return $record;
    }

    public function getRecordByColumn(string $column, $value, array $requestedColumns = [])
    {
        return $this->getRecordByCondition($column . ' = ' . $value, $requestedColumns);
    }

    public function getRecordById(int $id, array $requestedColumns = [])
    {
        return $this->getRecordByCondition('id = ' . $id, $requestedColumns);
    }

    public function getRecordByUrl(string $url, array $requestedColumns = [])
    {
        return $this->getRecordByCondition("url = '{$url}'", $requestedColumns);
    }

    public function useDefaultHtmlRendering()
    {
        $this->html=$this->name;
    }

    final public function getDateCreation()
    {
        return $this->dateCreation;
    }

    final public function getDateModified()
    {
        if (!$this->dateModified) {
            return '';
        }
        return $this->dateModified;
    }

    final public function getRecord()
    {
        return $this->record;
    }

    final public function addHtmlBefore(string $html)
    {
        $this->html=$html.$this->html;
    }

    final public function addHtmlAfter(string $html)
    {
        $this->html.=$html;
    }

    final public function setHtml(string $html)
    {
        $this->html=$html;
    }

    final public function getHtml()
    {
        return $this->html;
    }

    public function delete(): bool
    {
        global $_messages;

        $query = "DELETE FROM $this->dbTable WHERE id = $this->id";

        if (!Database::query($query)) {
            $_messages->add(ALERT_TYPE_ERROR, _('Error deleting ' . static::$classNameReadable));
            return false;
        } else {
            $_messages->add(ALERT_TYPE_CONFIRM, _(static::$classNameReadable . ' deleted'));
            return true;
        }

        return false;
    }

    public function getNextSequenceId()
    {
        return Database::getNextSequenceIdFromTable($this->dbTable);
    }

    public function getCurrentSequenceId()
    {
        return Database::getCurrentSequenceIdFromTable($this->dbTable);
    }
}
