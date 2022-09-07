<?php

class Navigations extends DbTable
{
    protected $dbTable = 'navigations';
    protected $recordClassToCreate = 'Navigation';

    public function renderHtml(): string
    {
        $html='';
        return $html;
    }
}

class Navigation extends DbTableRecord
{
    protected $dbTable = 'navigations';
    protected $cssClass = '';

    public function addCssClass(string $cssClass): void
    {
        $this->cssClass .= ($this->cssClass ? ' ' : '') . $cssClass;
        return;
    }

    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    public function renderHtml(): string
    {
        $html='';
        return $html;
    }

    public function markCurrentActiveItemFromUrl(string $currentUrl, string $cssClass = ''): void
    {
        $selected = false;
        foreach ($this->voices as $voice) {
            foreach ($voice->getSubVoices() as $subVoice) {
                if ($subVoice->getUrl() == $currentUrl) {
                    $selected = true;
                    $subVoice->addCssClass('selected');
                }
            }
            if ($voice->getUrl() == $currentUrl or $selected) {
                $selected = true;
                $voice->addCssClass('selected');
            }
        }
        return;
    }
}

class NavigationVoice extends BaseModelMultilanguage
{
    protected $subVoices = [];
    protected $parent = 0;
    protected $cssClass = '';
    protected $isOpenByDefault = false;

    public function addCssClass(string $cssClass): void
    {
        $this->cssClass .= ($this->cssClass ? ' ' : '') . $cssClass;
        return;
    }

    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    public function renderHtml(): string
    {
        $html = '';
        if (is_file($_SERVER['APP_ROOT'].'views/sections/navigation-voice.php')) {
            include $_SERVER['APP_ROOT'].'views/sections/navigation-voice.php';
        }
        return $html;
    }

    public function getSubVoices(): array
    {
        return $this->subVoices;
    }

    public function addSubVoice(NavigationVoiceNoDb $subVoice)
    {
        $this->subVoices[$subVoice->id]=$subVoice;
    }

    public function getParent(): int
    {
        return $this->parent;
    }

    public function isOpenByDefault(): bool
    {
        return $this->isOpenByDefault;
    }

    public function setOpenByDefault(bool $isOpen = true): void
    {
        $this->isOpenByDefault = $isOpen;
        return;
    }

    public function setChecked(bool $checked = true): void
    {
        $this->setOpenByDefault($checked);
        return;
    }
}

class NavigationNoDb extends BaseModelMultilanguageNoDb
{
    protected $voices = [];
    protected $url = '';
    protected $cssClass = '';

    public function addCssClass(string $cssClass): void
    {
        $this->cssClass .= ($this->cssClass ? ' ' : '') . $cssClass;
        return;
    }

    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    public function renderHtml(): string
    {
        $html='';
        return $html;
    }

    public function addVoice(NavigationVoiceNoDb $voice): void
    {
        $this->voices[$voice->id] = $voice;
        return;
    }

    public function getVoices(): array
    {
        return $this->voices;
    }

    public function getVoiceById(int $id): NavigationVoiceNoDb
    {
        try {
            if (isset($this->voices[$id])) {
                return $this->voices[$id];
            } else {
                new LogicException(_('The requested voice does not exist in the navigation. Check navigation settings.'));
            }
        } catch (LogicException $exception) {
        }
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
        return;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function markCurrentActiveItemFromUrl(string $currentUrl, string $cssClass = '') //: void
    {
        foreach ($this->voices as $voice) {
            $selected = false;
            foreach ($voice->getSubVoices() as $subVoice) {
                if ($subVoice->getUrl() == $currentUrl) {
                    $selected = true;
                    $subVoice->addCssClass('selected');
                } else {
                    $selected = false;
                }
            }

            if ($voice->getUrl() == $currentUrl or $selected) {
                $selected = true;
                $voice->addCssClass('selected');
            }
        }
        return;
    }
}

class NavigationVoiceNoDb extends BaseModelMultilanguageNoDb
{
    protected $url = '';
    protected $subVoices = [];
    protected $parent = 0;
    protected $cssClass = '';
    protected $isOpenByDefault = false;

    public function __construct(int $id=0, int $parent=0)
    {
        if ($id) {
            parent::__construct($id);
        }

        if ($parent) {
            $this->setParent($parent);
        }
    }

    public function addCssClass(string $cssClass): void
    {
        $this->cssClass .= ($this->cssClass ? ' ' : '') . $cssClass;
        return;
    }

    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
        return;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function renderHtml(): string
    {
        $html='';
        if (is_file($_SERVER['APP_ROOT'].'views/sections/navigation-voice-nodb.php')) {
            include $_SERVER['APP_ROOT'].'views/sections/navigation-voice-nodb.php';
        }
        return $html;
    }

    public function getSubVoices(): array
    {
        return $this->subVoices;
    }

    public function addSubVoice(NavigationVoiceNoDb $subVoice): void
    {
        $this->subVoices[$subVoice->id] = $subVoice;
        return;
    }

    public function setParent(int $parent): void
    {
        $this->parent = $parent;
        return;
    }

    public function getParent(): int
    {
        return $this->parent;
    }

    public function isOpenByDefault(): bool
    {
        return $this->isOpenByDefault;
    }

    public function setOpenByDefault(bool $isOpen = true): void
    {
        $this->isOpenByDefault = $isOpen;
        return;
    }

    public function setChecked(bool $checked = true): void
    {
        $this->setOpenByDefault($checked);
        return;
    }
}
