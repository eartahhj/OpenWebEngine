<select id="<?=$this->id?>" name="<?=$this->name?>"
<?=$this->returnHtmlCustomAttributes()?>
>
<option value=""></option>
<?php foreach($this->options as $optionValue=>$optionLabel):?>
    <option value="<?=$optionValue?>"<?=($optionValue == $this->value ? ' selected="selected"' : '')?>>
    <?=htmlspecialchars($optionLabel)?>
    </option>
<?php endforeach?>
</select>
