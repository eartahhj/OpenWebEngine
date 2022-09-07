<?php

use DifferentWebEngine\Forms\Form;

$form = new Form();
$form->setId('Bhc4Xz9d');
$form->setMethod('post');
$form->setAction(Router::getUrl());
$form->addSubmit('save', _('Send'), 'btn-default btn-save');

$field = new InputText();
$field->setName('author_name');
$field->setLabel(_('Name'));
$field->setMinLength(1);
$field->setMaxLength(200);
$field->addValidator(new ValidatorString());
$field->setRequired(false);
$form->addField($field);

$field = new InputTextarea();
$field->setName('text');
$field->setLabel('Text');
$field->setMinLength(1);
$field->setMaxLength(5000);
$field->addValidator(new ValidatorString());
$field->setRequired(true);
$form->addField($field);

$field = new InputEmail();
$field->setName('email');
$field->setLabel(_('Email'));
$field->setMinLength(1);
$field->setMaxLength(200);
$field->addValidator(new ValidatorString());
$field->addValidator(new ValidatorEmail());
$field->setRequired(false);
$form->addField($field);

foreach ($form->getFields() as $field) {
    $field->setFormId($form->getId());
}
