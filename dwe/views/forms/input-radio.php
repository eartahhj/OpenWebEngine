<?php $i = 1?>
<?php foreach ($this->acceptedValues as $value => $label):?>
    <input id="<?=$this->id?>-<?=$i?>" type="<?=$this->type?>" name="<?=$this->name?>" value="<?=$value?>"
    <?php
    if (
        (isset($this->preSelectedValues[$value]) and $this->preSelectedValues[$value] == $value)
        or
        (isset($_REQUEST[$this->name]) and $_REQUEST[$this->name] == $value)
        or
        (getDbColumnBooleanValue($this->value) === getDbColumnBooleanValue($value))
    ) {
        echo ' checked="checked"';
    }
    ?>
    <?php $this->returnHtmlCustomAttributes()?>
    ><?=$label?>
    <?php $i++?>
<?php endforeach;?>
