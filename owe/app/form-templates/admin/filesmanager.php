<?php

use OpenWebEngine\Forms\Form;

$form = new Form();
$form->setId('xC31s7rZa');
$form->setMethod('post');
$form->setAction(RouterAdmin::getUrl());
$form->setEnctypeMultipart();
$form->addSubmit('save', _('Save'), 'btn-default btn-save');

if ($action == 'edit' and $fileId) {
    $form->addSubmit('delete', _('Delete'), 'btn-default btn-delete', "return confirm('" . _('Do you really want to delete this file?') . "')");
}

$field = new InputText();

foreach ($_languages as $languageTag=>$languageName) {
    $field = new InputText();
    $field->setName('title_'.$languageTag);
    $field->setLabel(str_replace('###', $languageName, _('Title (###)')));
    $field->setMinLength(5);
    $field->setMaxLength(100);
    $field->addValidator(new ValidatorString());
    if ($languageTag == 'it') {
        $field->setRequired(true);
    }
    $form->addField($field);

    $field = new InputText();
    $field->setName('url_'.$languageTag);
    $field->setLabel(str_replace('###', $languageName, _('URL (###)')));
    $field->setMinLength(1);
    $field->setMaxLength(100);
    $field->addValidator(new ValidatorString());
    if ($languageTag == 'en') {
        $field->setRequired(true);
    }
    $form->addField($field);
}

$field = new InputFile();
$field->setName('filename');
$field->setLabel(_('File'));
$field->allowCustomFileNames();
if (!$file->getFileName()) {
    $field->setRequired(true);
} else {
    $field->setHtmlAfter('<p>' . _('Current file:') . ' <a href="' . Config::$mediaLibraryRelativeDir . $file->getFileName() . '">' . $file->getFileName() . '</a></p>');
}
$field->setValuePrefix($file->getId() . '-');
$field->setUploadDirectory(Config::$mediaLibraryAbsoluteDir);
$form->addField($field);

$field = new InputRadio();
$field->setName('hidden');
$field->setLabel(_('Hidden'));
$field->setAcceptedValues(['true' => _('Yes'), 'false' => _('No')]);
if (!isset($_REQUEST['save'])) {
    $field->setPreSelectedValues(['false' => 'false']);
}
$form->addField($field);

if ($action == 'edit') {
    $form->setFieldsValuesFromRecord($file->getRecord());
}

foreach ($form->getFields() as $field) {
    $field->setFormId($form->getId());
}
