<?php

class FilesBase extends DbTable
{
    protected $dbTable = 'files';
    protected $recordClassToCreate = 'File';
    protected $columnNameForOptionsList = 'id';

    public function renderHtml(): string
    {
        $html = '';
        return $html;
    }
}

class FileBase extends DbTableRecord
{
    protected $dbTable = 'files';
    protected $url = '';
    protected $filename = '';
    protected $hidden = false;
    protected $adminCreator = null;
    protected $adminLastEditor = null;
    protected $mimeType = '';
    protected $extension = '';
    protected static $classNameReadable = '';

    public function __construct()
    {
        parent::__construct();

        self::$classNameReadable = _('File');
    }


    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);

        $this->url = $object->url ?? '';
        $this->filename = $object->filename ?? '';

        $this->extension = \returnFileExtension($this->filename);

        if (isset($object->hidden) and $object->hidden == 'true') {
            $this->hidden = true;
        }

        $this->adminCreator = $object->admin_creator ?? null;
        $this->adminLastEditor = $object->admin_last_editor ?? null;
        $this->mimeType = $object->mimetype ?? '';

        return;
    }

    public function renderHtml(): string
    {
        $html='';
        return $html;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getFormattedUrl()
    {
        global $_language;

        return $url = $this->{'url_' . $_language} . '-' . $this->id;
    }

    final public function getFileName(): string
    {
        return $this->filename;
    }

    final public function getExtension(): string
    {
        return $this->extension;
    }

    final public function getFileNameWithouExtension(): string
    {
        return str_replace('.' . $this->extension, '', $this->filename);
    }

    final public function isHidden(): bool
    {
        return $this->hidden;
    }

    final public function setAdminCreator(int $adminId): void
    {
        global $_db, $_messages;
        $admin=new Admin();
        $record = $admin->getRecordById($adminId);
        $admin->setDataByObject($record);
        $this->adminCreator=clone $admin;
        unset($admin, $record);
        return;
    }

    final public function getAdminCreator(): Admin
    {
        return $this->adminCreator;
    }

    final public function setAdminLastEditor(int $adminId): void
    {
        global $_db, $_messages;
        $admin=new Admin();
        $record = $admin->getRecordById($adminId);
        $admin->setDataByObject($record);
        $this->adminLastEditor=clone $admin;
        unset($admin, $record);
        return;
    }

    final public function getAdminLastEditor(): Admin
    {
        return $this->adminLastEditor;
    }

    final public function getMimeType(): string
    {
        return $this->mimeType;
    }

    final public function delete(): bool
    {
        global $_messages;

        $directory = $this->uploadDirectory ? $this->uploadDirectory : Config::$uploadsAbsoluteDir;

        if (!$this->deleteFromDirectory($directory)) {
            return false;
        } else {
            if (!Database::queryDelete('files', 'id', $fileId)) {
                return false;
            } else {
                $_messages->add(ALERT_TYPE_CONFIRM, _('File deleted'));
                return true;
            }
        }
    }

    final public function deleteFromDirectory(string $directory): bool
    {
        global $_messages;

        if (!$this->filename or !$directory or !$this->uploadDirectory) {
            throw new LogicException(_('Filename or directory not found. Error deleting the file.'));
            return false;
        }

        $file = $directory . $this->filename;

        if (!is_file($file)) {
            $_messages->add(ALERT_TYPE_ERROR, $query, _('File not found in the filesystem'));
            return false;
        }

        if (!deleteFile($file)) {
            $_messages->add(ALERT_TYPE_ERROR, $query, _('Error deleting file from filesystem'));
            return false;
        }

        return false;
    }
}

if (is_file($_SERVER['APP_ROOT'].'models/custom/files-custom.php')) {
    include_once $_SERVER['APP_ROOT'].'models/custom/files-custom.php';
} else {
    throw new LogicException('Missing custom files file');
}
