<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';
?>

<h1><?=$title?></h1>
<?php
$form->view();
?>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-footer.php';
