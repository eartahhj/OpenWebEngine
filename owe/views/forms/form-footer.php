<?php
if (!isset($form) and !empty($this)) {
    $form = $this;
}
?>

<input type="hidden" name="formID" value="<?=$form->getId()?>" />

<?php if (!empty($form->getSubmits())):?>
    <div class="form-submits">
    <?php foreach ($form->getSubmits() as $submit):?>
        <?=$submit->view()?>
    <?php endforeach?>
    </div>
<?php endif?>
</form>
