<?php
require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-header.php';
?>

<h2>Welcome <?=htmlspecialchars($_admin->getUsername())?></h2>

<?php
require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-footer.php';
