<?php
// NOTE: Currently not used, the idea is to let an admin use only some modules based on the website configuration

class Module
{
    protected $name = '';
    protected $enabled = false;

    public function __construct(string $name, bool $enabled = true)
    {
        $this->name = $name;
        $this->enabled=$enabled;
        return;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }
}

class Modules
{
    protected $enabledModules = [];
    protected $disabledModules = [];
    protected $allModules = [];

    public function add(string $moduleName, bool $enabled = true): void
    {
        $module = new Module($moduleName, $enabled);
        $this->allModules[] = $module;
        if ($enabled) {
            $this->enabledModules[] = $module;
        } else {
            $this->disabledModules[] = $module;
        }
        return;
    }

    public function isModuleEnabled(string $moduleName): bool
    {
        if (isset($this->allModules[$moduleName])) {
            return $this->allModules[$moduleName]->getEnabled();
        }
    }
}

$_modules = new Modules();
$_modules->add('pages');
