<?php

if (!class_exists('NavigationNoDb') or !class_exists('NavigationVoiceNoDb')) {
    require_once $_SERVER['APP_ROOT'] . 'models/navigation.php';
}

class NavigationAdminVoice extends NavigationVoiceNoDb
{
}

class NavigationAdmin extends NavigationNoDb
{
    public function renderHtml(): string
    {
        $html = parent::renderHtml();
        if (is_file($_SERVER['APP_ROOT'] . 'views/sections/navigation-admin.php')) {
            include_once $_SERVER['APP_ROOT'] . 'views/sections/navigation-admin.php';
        } else {
            throw new LogicException('Could not load navigation-admin view');
        }
        return $html;
    }
}

$_adminNav = new NavigationAdmin();
$_adminNav->setTitle(_('Administration'));
$_adminNav->setUrl(Config::$baseURLAdmin);

$adminNavVoice = new NavigationAdminVoice();
$adminNavVoice->setId(100);
$adminNavVoice->setTitle(_('Manage pages'));
$adminNavVoice->setUrl('/admin/pages');
$adminNavVoice->setChecked(true);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(101);
$subVoice->setParent(100);
$subVoice->setTitle(_('View all pages'));
$subVoice->setUrl('/admin/pages/list');
$adminNavVoice->addSubVoice($subVoice);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(102);
$subVoice->setParent(100);
$subVoice->setTitle(_('Page editor'));
$subVoice->setUrl('/admin/pages/edit');
$adminNavVoice->addSubVoice($subVoice);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(103);
$subVoice->setParent(100);
$subVoice->setTitle(_('New page'));
$subVoice->setUrl('/admin/pages/create');
$adminNavVoice->addSubVoice($subVoice);

$_adminNav->addVoice($adminNavVoice);

$adminNavVoice = new NavigationAdminVoice();
$adminNavVoice->setId(200);
$adminNavVoice->setTitle(_('Files manager'));
$adminNavVoice->setUrl('/admin/filesmanager/');

$subVoice = new NavigationAdminVoice();
$subVoice->setId(201);
$subVoice->setParent(200);
$subVoice->setTitle(_('View all files'));
$subVoice->setUrl('/admin/filesmanager/list');
$adminNavVoice->addSubVoice($subVoice);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(202);
$subVoice->setParent(200);
$subVoice->setTitle(_('Upload new file'));
$subVoice->setUrl('/admin/filesmanager/create');
$adminNavVoice->addSubVoice($subVoice);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(203);
$subVoice->setParent(200);
$subVoice->setTitle(_('Edit a file'));
$subVoice->setUrl('/admin/filesmanager/edit');
$adminNavVoice->addSubVoice($subVoice);

$_adminNav->addVoice($adminNavVoice);

$adminNavVoice = new NavigationAdminVoice();
$adminNavVoice->setId(900);
$adminNavVoice->setTitle(_('Settings'));
$adminNavVoice->setUrl('/admin/settings');
$adminNavVoice->setChecked(true);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(901);
$subVoice->setTitle(_('Change language'));
$subVoice->setUrl('/admin/settings/language');
$adminNavVoice->addSubVoice($subVoice);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(902);
$subVoice->setTitle(_('Change password'));
$subVoice->setUrl('/admin/settings/password');
$adminNavVoice->addSubVoice($subVoice);

$subVoice = new NavigationAdminVoice();
$subVoice->setId(903);
$subVoice->setTitle(_('Change theme'));
$subVoice->setUrl('/admin/settings/theme');
$adminNavVoice->addSubVoice($subVoice);

$_adminNav->addVoice($adminNavVoice);

$adminNavVoice = new NavigationAdminVoice();
$adminNavVoice->setId(5000);
$adminNavVoice->setTitle(_('Logout'));
$adminNavVoice->setUrl('/admin/logout');
$adminNavVoice->setChecked(true);

$_adminNav->addVoice($adminNavVoice);

$_adminNav->markCurrentActiveItemFromUrl($_SERVER['PHP_SELF']);
