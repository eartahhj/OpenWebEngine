<?php

use DifferentWebEngine\Forms\Form;

$form = new Form();
$form->setId('w8m2Yh1');
$form->setMethod('post');
$form->setAction(RouterAdmin::getUrl());
$form->addSubmit('login', _('Login'), 'btn-default btn-login');

$field = new InputText();
$field->setName('username');
$field->setLabel(_('Username'));
$field->setMinLength(5);
$field->setMaxLength(100);
$validator = new ValidatorString();
$validator->setMinLength(5);
$validator->setMaxLength(100);
$field->setRequired(true);
$form->addField($field);

$field = new InputPassword();
$field->setName('password');
$field->setLabel(_('Password'));
$field->setMinLength(PASSWORD_MIN_LENGTH);
$field->setMaxLength(PASSWORD_MAX_LENGTH);
$field->addValidator(new ValidatorString());
$field->setRequired(true);
$form->addField($field);

foreach ($form->getFields() as $field) {
    $field->setFormId($form->getId());
}
