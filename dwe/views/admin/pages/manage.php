<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';
?>

<h1><?=$title?></h1>

<?php
include $_SERVER['APP_ROOT'] . 'views/forms/form-header.php';
?>

<?php
/* Alternative way
<?php foreach ($form->getFields() as $field):?>
    <?php $field->view()?>
<?php endforeach?>
*/
?>

<?php
if ($action == 'edit') {
    $form->getFieldByName('category')->view();
}

$form->getFieldByName('title')->view();
?>
<div class="fields-multilanguage">
    <div class="fields-multilanguage-handlers">
        <?php foreach ($_languages as $languageTag => $languageName):?>
        <input type="checkbox" id="fields-multilanguage-handler-<?=$languageTag?>" class="sr-only hidden handler">
        <label for="fields-multilanguage-handler-<?=$languageTag?>" class="hidden"><?=$languageName?></label>
        <?php endforeach?>
    </div>

    <div class="fields-multilanguage-fields">
        <?php foreach ($_languages as $languageTag => $languageName):?>
        <div class="fields-language fields-language-<?=$languageTag?>" data-handler="fields-multilanguage-handler-<?=$languageTag?>">
            <?php $form->getFieldByName('title_' . $languageTag)->view()?>
            <?php $form->getFieldByName('seo_title_' . $languageTag)->view()?>
            <?php $form->getFieldByName('html_' . $languageTag)->view()?>
            <?php $form->getFieldByName('url_' . $languageTag)->view()?>
            <?php $form->getFieldByName('seo_description_' . $languageTag)->view()?>
            <?php $form->getFieldByName('tags_' . $languageTag)->view()?>
            <?php $form->getFieldByName('hidden_' . $languageTag)->view()?>
            <?php $form->getFieldByName('youtube_video_link_' . $languageTag)->view()?>
            <?php $form->getFieldByName('youtube_video_title_' . $languageTag)->view()?>
        </div>
        <?php endforeach?>
    </div>
</div>

<?php
if ($action == 'edit') {
    $form->getFieldByName('image')->view();
    $form->getFieldByName('youtube_video_preview')->view();
}
?>

<?php
include $_SERVER['APP_ROOT'] . 'views/forms/form-footer.php';
?>

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-footer.php';
