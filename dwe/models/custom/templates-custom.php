<?php

class TemplateCustom extends Template
{
    protected $title_it='';
    protected $title_en='';
    protected $title_es='';
    protected $title_de='';
    protected $title_fr='';

    final public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->setPropertyMultilangFromObject('title', $object);
        return;
    }
}

class TemplateCategoryIndex extends TemplateCustom
{
    protected $name = 'CategoryIndex';
    protected $fileName = 'category-index';
}
