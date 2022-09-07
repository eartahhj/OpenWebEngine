<?php
$newTheme = '';

if (isset($_GET['theme'])) {
    $newTheme = trim($_GET['theme']);
}

if ($newTheme) {
    setcookie('theme', $newTheme, time() + 63072000, '/admin', '', true, true);
}
?>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';
?>

<h2><?=_('Change theme')?></h2>

<h4><?=_('Set your favorite theme for the admin panel')?></h4>
<ul>
    <li><a href="?theme=dark">Dark</a></li>
    <li><a href="?theme=light">Light</a></li>
</ul>

<?php
require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-footer.php';
?>
