<?php
$newLanguage = '';

if (isset($_GET['lang'])) {
    $newLanguage = trim($_GET['lang']);
}

if ($newLanguage) {
    if (isset($_languages[$newLanguage])) {
        setcookie('lang', $newLanguage, time() + 63072000, '/admin', '', true, true);
    } else {
        $_messages->add(ALERT_TYPE_ERROR, _('Could not set this language, maybe it is not configured in this website installation'));
    }
}
?>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';
?>

<h2><?=_('Change language')?></h2>

<h4><?=_('Set your favorite language for the admin panel')?></h4>
<ul>
    <?php foreach ($_languages as $langTag => $langName):?>
        <li><a href="?lang=<?=$langTag?>"><?=$langName?></a></li>
    <?php endforeach?>
</ul>

<?php
require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-footer.php';
?>
