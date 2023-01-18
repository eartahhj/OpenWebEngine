<?php
if (Router::getAction() and Router::getAction() != 'index') {
    if (is_file(__DIR__ . '/' . Router::getAction() . '.php')) {
        require_once __DIR__ . '/' . Router::getAction() . '.php';
        exit();
    } else {
        require_once $_SERVER['APP_ROOT'] . 'views/404-notfound.php';
    }
}
?>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';
?>

<h2><?=_('Settings')?></h2>
<ul>
    <li><a href="<?=$_SERVER['SCRIPT_URI']?>language"><?=_('Change language')?></a></li>
    <li><a href="<?=$_SERVER['SCRIPT_URI']?>password"><?=_('Change password')?></a></li>
    <li><a href="<?=$_SERVER['SCRIPT_URI']?>theme"><?=_('Change theme')?></a></li>
</ul>

<?php
require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-footer.php';
