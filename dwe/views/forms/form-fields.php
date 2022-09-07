<?php
if (!isset($form) and !empty($this)) {
    $form = $this;
}
?>

<?php foreach($form->fields as $field):?>
    <?=$field->view()?>
<?php endforeach?>
