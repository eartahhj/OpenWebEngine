<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';
?>
<h1>Login</h1>
<?php
if (!$_admin->isLogged()):
    echo $form->generateAndReturnHtml();
else:
?>
<p>Welcome <?=$_admin->getUsername()?></p>
<?php
endif;
?>

<?php
require_once $_SERVER['APP_ROOT'].'views/templates/template-admin-footer.php';
