<?php

class Comments extends DbTable
{
    protected $dbTable = 'comments';
    protected $recordClassToCreate = 'Comment';
    protected $columnNameForOptionsList = 'id';

    public function renderHtml(): string
    {
        $html='';
        return $html;
    }

    public function getAllCommentsByPageId(int $pageId)
    {
        $records = $this->getAllRecords("id_page = {$pageId} AND approved");

        if ($records) {
            $this->setRecords($records);
        }

        if ($this->prepareList()) {
            return $this->list;
        }
    }

    public function insertComment(array $fields): bool
    {
        return $this->insertRecord($fields);
    }
}

class CommentBase extends DbTableRecord
{
    protected $dbTable = 'comments';
    protected $text = '';
    protected $authorName = '';
    protected $userId = 0;
    protected $approved = false;
    protected $pageId = 0;

    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->text = $object->text ?? '';
        $this->authorName = $object->author_name ?? '';
        $this->userId = $object->id_user ?? 0;
        if (isset($object->approved) and ($object->approved == 't' or $object->approved == '1')) {
            $this->approved = true;
        } else {
            $this->approved = false;
        }
        $this->pageId = $object->id_page ?? 0;
        return;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function renderHtml(): string
    {
        $html = '';
        return $html;
    }
}

if (is_file($_SERVER['APP_ROOT'] . 'models/custom/comments-custom.php')) {
    include_once $_SERVER['APP_ROOT'] . 'models/custom/comments-custom.php';
} else {
    throw new LogicException('Missing custom news file');
}
