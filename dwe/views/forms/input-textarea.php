<textarea id="<?=$this->id?>" name="<?=$this->name?>"<?=($this->rows ? ' rows="' . $this->rows . '"' : '') . ($this->cols ? ' cols="' . $this->cols . '"' : '')?>
<?=$this->returnHtmlCustomAttributes()?>
><?=$this->value?></textarea>
