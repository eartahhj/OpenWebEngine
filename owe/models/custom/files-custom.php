<?php

class Files extends FilesBase
{
}

class File extends FileBase
{
    protected $title_it = '';
    protected $title_en = '';
    protected $url_it = '';
    protected $url_en = '';

    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->title_it = $object->title_it ?? '';
        $this->title_en = $object->title_en ?? '';
        $this->url_it = $object->url_it ?? '';
        $this->url_en = $object->url_en ?? '';
        return;
    }
}
