<?php

class Templates extends DbTable
{
    protected $dbTable='page_templates';
    protected $recordClassToCreate='Template';
    protected $list=[];
}

class Template extends DbTableRecord
{
    protected $dbTable = 'page_templates';
    protected $title = '';
    protected $name = '';
    protected $fileName = '';
    protected $headerHtml = '';
    protected $footerHtml = '';
    protected $enabled = true;
    protected $adminCreator = null;
    protected $adminLastEditor = null;
    protected $entities = [];
    protected $css = [];
    protected $javascript = [];
    protected $isStarted = false;
    protected $isClosed = false;

    public function loadTemplate(string $templateFileName = ''): bool
    {
        if ($templateFileName) {
            $this->fileName = $templateFileName;
        }

        if (!$this->record = $this->getRecordByCondition("file_name = '{$this->fileName}'")) {
            return false;
        }

        $this->setDataByObject($this->record);

        return true;
    }

    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->title = $object->title ?? '';
        $this->fileName = $object->file_name ?? '';
        $this->headerHtml = $object->html_header ?? '';
        $this->footerHtml = $object->html_footer ?? '';
        $this->enabled = $object->enabled ?? true;
        $this->adminCreator = $object->admin_creator ?? null;
        $this->adminLastEditor = $object->adminLastEditor ?? null;
        return;
    }

    public function renderHtml(): string
    {
        $html='';
        return $html;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFileName(): string
    {
        return strtolower($this->fileName);
    }

    public function getHeaderHtml(): string
    {
        return $this->headerHtml;
    }

    public function getFooterHtml(): string
    {
        return $this->footerHtml;
    }

    final public function setAdminCreator(int $adminId): void
    {
        global $_db, $_messages;
        $admin=new AdminCustom();
        $record=$admin->getRecordById($adminId);
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
        $admin=new AdminCustom();
        $record=$admin->getRecordById($adminId);
        $admin->setDataByObject($record);
        $this->adminLastEditor=clone $admin;
        unset($admin, $record);
        return;
    }

    final public function getAdminLastEditor(): Admin
    {
        return $this->adminLastEditor;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }

    public function addEntity(string $name, $value): void
    {
        $this->entities[$name]=$value;
        return;
    }

    public function getEntity(string $name)
    {
        return $this->entities[$name] ?? null;
    }

    public function addCss(string $fileName)
    {
        $this->css[]=$fileName;
    }

    public function getCss(): array
    {
        return $this->css;
    }

    public function addJavascript(string $fileName)
    {
        $this->javascript[]=$fileName;
    }

    public function setStarted(bool $isStarted)
    {
        $this->isStarted=$isStarted;
    }

    public function setClosed(bool $isClosed)
    {
        $this->isClosed=$isClosed;
    }

    public function getJavascript(): array
    {
        return $this->javascript;
    }

    public function render(): void
    {
        echo $this->html;
        return;
    }

    public function exit(): void
    {
        global $_config, $_messages;

        if ($_messages->getList()) {
            echo $_messages;
        }

        exit();
    }
}

class TemplateStandard extends Template
{
    protected $name = 'Standard';
    protected $fileName = 'standard';
}

class TemplateHomepage extends Template
{
    protected $name = 'Homepage';
    protected $fileName = 'homepage';
}


class TemplateAdmin
{
    protected $isStarted='';
    protected $isClosed='';

    public function setStarted(bool $isStarted)
    {
        $this->isStarted=$isStarted;
    }

    public function setClosed(bool $isClosed)
    {
        $this->isClosed=$isClosed;
    }

    public function exit(): void
    {
        global $_config, $_messages;

        if ($_messages->getList()) {
            echo $_messages;
        }
        exit();
    }
}

if (is_file($_SERVER['APP_ROOT'].'models/custom/templates-custom.php')) {
    include_once $_SERVER['APP_ROOT'].'models/custom/templates-custom.php';
} else {
    throw new LogicException('Missing custom templates file');
}
