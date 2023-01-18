<?php
if (!isset($form) and !empty($this)) {
    $form = $this;
}
?>

<form id="<?=$form->getId()?>" action="<?=$form->getAction()?>" method="<?=$form->getMethod()?>"
<?php
if($form->getEnctype()) {
    echo ' enctype="' . $form->getEnctype() . '"';
}
?>
>
