<?php

if (!class_exists('NavigationNoDb') or !class_exists('NavigationVoiceNoDb')) {
    require_once $_SERVER['APP_ROOT'] . 'models/navigation.php';
}

class NavigationGuestVoice extends NavigationVoiceNoDb
{
}

class NavigationGuest extends NavigationNoDb
{
    public function renderHtml(): string
    {
        $html = parent::renderHtml();
        if (is_file($_SERVER['APP_ROOT'] . 'views/sections/navigation-guest.php')) {
            include_once $_SERVER['APP_ROOT'] . 'views/sections/navigation-guest.php';
        } else {
            throw new LogicException('Could not load navigation-guest view');
        }
        return $html;
    }
}

$_templateNav = new NavigationGuest();
$_templateNav->setUrl(Config::$baseURL);

$pageCategories = new PageCategories();
$pageCategories->setRecords($pageCategories->getAllRecords('id != 1000'));
$pageCategories->prepareList();
foreach ($pageCategories->getList() as $id => $category) {
    $templateNavVoice = new NavigationGuestVoice();
    $templateNavVoice->setId($id);
    $templateNavVoice->setTitle($category->getPropertyMultilang('title'));
    $templateNavVoice->setUrl(Config::$baseURLLanguage . $category->getPropertyMultilang('url') . '-' . $id);
    $_templateNav->addVoice($templateNavVoice);
}
