<?php $i = 1?>
<?php foreach ($this->acceptedValues as $value => $label):?>
    <input id="<?=$this->id?>-<?=$i?>" type="<?=$this->type?>" name="<?=$this->name?>-<?=$value?>" value="t"
    <?php
    if (
        isset($this->preSelectedValues[$value]) or
        isset($_REQUEST[$this->name . '-' . $value]) and
        $_REQUEST[$this->name . '-' . $value] == 't'
    ) {
        echo ' checked="checked"';
    }
    ?>
    <?=$this->returnHtmlCustomAttributes()?>
    ><?=$label?>
    <?php $i++?>
<?php endforeach?>
