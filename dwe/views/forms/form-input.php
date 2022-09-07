<div class="field field-<?=$this->type?><?=($this->cssClass ? ' ' . $this->cssClass : '')?>">
<?=$this->htmlBefore?>

<?php if($this->getLabel()):?>
    <label for="<?=$this->id?>"><?=$this->label?></label>
    <?php if ($this->isRequired()):?>
        <span class="required-symbol">*</span>
    <?php endif?>
<?php endif?>

<?php
if ($this->getErrors()):?>
    <div class="field-errors">
    <?=$this->returnHtmlErrors()?>
    </div>
<?php endif?>

<div class="field-html">
    <?php include $_SERVER['APP_ROOT'] . 'views/forms/input-' . $this->type . '.php'?>
</div>

<?=$this->htmlAfter?>
</div>
