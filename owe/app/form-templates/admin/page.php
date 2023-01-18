<?php

use OpenWebEngine\Forms\Form;

$form = new Form();
$form->setId('dRy3aWk4');
$form->setMethod('post');
$form->setAction(RouterAdmin::getUrl());
$form->setEnctypeMultipart();
$form->addSubmit('save', _('Save'), 'btn-default btn-save');

if ($action == 'edit' and $pageId) {
    $form->addSubmit('delete', _('Delete'), 'btn-default btn-delete', "return confirm('" . _('Do you really want to delete this page?') . "')");
}

if ($action == 'edit') {
    $field = new InputCheckbox();
    $field->setName('category');
    $field->setLabel(_('Categories'));
    $field->setAcceptedValues($allCategoriesKeyValueArray);
    if (!isset($_REQUEST['save'])) {
        $field->setPreSelectedValues($page->getCategoriesAsArrayKeyValue());
    }
    $field->dontSaveInDatabase();
    $form->addField($field);
}

$field = new InputText();
$field->setName('title');
$field->setLabel(_('Title'));
$field->setMinLength(1);
$field->setMaxLength(100);
$field->addValidator(new ValidatorString());
$field->setRequired(true);
$form->addField($field);

foreach($_languages as $languageTag => $languageName) {
    $field = new InputText();
    $field->setName('title_'.$languageTag);
    $field->setLabel(str_replace('###', $languageName, _('Title (###)')));
    $field->setMinLength(5);
    $field->setMaxLength(100);
    $field->addValidator(new ValidatorString());
    $field->setRequired(true);
    $form->addField($field);

    $field = new InputText();
    $field->setName('seo_title_'.$languageTag);
    $field->setLabel(str_replace('###', $languageName, _('SEO title (###)')));
    $field->setMinLength(5);
    $field->setMaxLength(100);
    $field->addValidator(new ValidatorString());
    $field->setRequired(true);
    $form->addField($field);

    $field = new InputTextarea();
    $field->setName('html_' . $languageTag);
    $field->setLabel(str_replace('###', $languageName, _('Content (###)')));
    $field->addValidator(new ValidatorString());
    $field->setRequired(false);
    $field->addCssClass('tinymce');
    $form->addField($field);

    $field = new InputText();
    $field->setName('url_'.$languageTag);
    $field->setLabel(str_replace('###', $languageName, _('URL (###)')));
    $field->setMinLength(1);
    $field->setMaxLength(100);
    $field->addValidator(new ValidatorString());
    if ($languageTag == 'it') {
        $field->setRequired(true);
    }
    $form->addField($field);

    $field = new InputTextarea();
    $field->setName('seo_description_' . $languageTag);
    $field->setLabel(str_replace('###', $languageName, _('SEO Description (###)')));
    $field->addValidator(new ValidatorString());
    $field->setRequired(false);
    $form->addField($field);

    $field = new InputText();
    $field->setName('tags_' . $languageTag);
    $field->setLabel(str_replace('###', $languageName, _('Tags (###)')));
    $field->setMinLength(1);
    $field->setMaxLength(100);
    $field->addValidator(new ValidatorString());
    $form->addField($field);

    $field = new InputRadio();
    $field->setName('hidden_' . $languageTag);
    $field->setLabel(str_replace('###', $languageName, _('Hidden (###)')));
    $field->setAcceptedValues(['true' => _('Yes'), 'false' => _('No')]);
    if ($action == 'create') {
        $field->setPreSelectedValues(['true' => 'true']);
    } else {
        $field->setPreSelectedValues($page->isHidden($languageTag) ? ['true' => 'true'] : ['false' => 'false']);
    }
    $form->addField($field);

    $field = new InputText();
    $field->setName('youtube_video_link_' . $languageTag);
    $field->setLabel(str_replace('###', $languageName, _('Youtube video link (###)')));
    $field->setMinLength(1);
    $field->setMaxLength(200);
    $field->addValidator(new ValidatorString());
    $form->addField($field);

    $field = new InputText();
    $field->setName('youtube_video_title_' . $languageTag);
    $field->setLabel(str_replace('###', $languageName, _('Youtube video title (###)')));
    $field->setMinLength(1);
    $field->setMaxLength(100);
    $field->addValidator(new ValidatorString());
    $form->addField($field);
}

// $field = new InputSelectNumeric();
// $field->setName('category');
// $field->setLabel(_('Category'));
// // $field->addValidator(new ValidatorNumber());
// $field->setOptions($_pageCategories);
// $field->setRequired(true);
// $form->addField($field);

if ($page->getId()) {
    $field = new InputFile();
    $field->setName('image');
    $field->setLabel(_('Image'));
    $field->allowCustomFileNames();
    // $field->addValidator(new ValidatorString());
    // $field->setRequired(true);
    if ($page->getImage()) {
        $field->setHtmlAfter('<p>' . _('Current file:') . ' <a href="' . Config::$uploadsRelativeDir . $page->getImage() . '">' . $page->getImage() . '</a></p>');
    }
    $field->setValuePrefix($page->getId() . '-');
    $field->setUploadDirectory(Config::$uploadsAbsoluteDir);
    $form->addField($field);

    $field = new InputFile();
    $field->setName('youtube_video_preview');
    $field->setLabel(_('Youtube video preview'));
    // $field->addValidator(new ValidatorString());
    // $field->setRequired(true);
    if ($page->getYoutubeVideoPreview()) {
        $field->setHtmlAfter('<p>' . _('Current file:') . ' <a href="' . Config::$uploadsRelativeDir . $page->getYoutubeVideoPreview() . '">' . $page->getYoutubeVideoPreview() . '</a></p>');
    }
    $field->setDefaultFileName($page->getId() . '-yt-preview');
    $field->setUploadDirectory(Config::$uploadsAbsoluteDir);
    $form->addField($field);
}

if ($action == 'edit') {
    $form->setFieldsValuesFromRecord($page->getRecord());
}

foreach($form->getFields() as $field) {
    $field->setFormId($form->getId());
}
