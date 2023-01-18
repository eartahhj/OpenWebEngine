<?php

$action = Router::getAction();

if (!$action or $action == 'list') {
    Router::redirectTo('/admin/pages/list');
}

if ($action != 'edit' and $action != 'create') {
    exit();
}

require_once $_SERVER['APP_ROOT'] . 'vendor/forms.php';
require_once $_SERVER['APP_ROOT'] . 'models/pages.php';
require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

$_template = new TemplateAdmin();

$pageId = (int)Router::getParams();
$page = new Page();
$pageRecord = null;

if ($action == 'edit') {
    if (!$pageId) {
        Router::redirectTo('/admin/pages/');
    }

    $pageRecord = $page->getRecordById($pageId);

    if (!$pageRecord) {
        $_messages->add(ALERT_TYPE_ERROR, _('No data found'));
        $page = null;
        $_template->exit();
    } else {
        $page->setDataByObject($pageRecord);
    }
}

$page->loadCategories();

$allCategories = new PageCategories();
$allCategories->setRecords($allCategories->getAllRecords());
$allCategories->prepareList();
$allCategoriesKeyValueArray = [];

foreach ($allCategories->getList() as $category) {
    $allCategoriesKeyValueArray[$category->getId()] = $category->returnDefaultTitle();
}

require_once $_SERVER['APP_ROOT'] . 'app/form-templates/admin/page.php';

$oldImage = $page->getImage();

if (isset($_REQUEST['save'])) {
    if (!$form->validateFields()) {
        $form->renderErrors();
    } else {
        if ($action == 'edit') {
            if (Database::queryUpdate('pages', $form->getFieldsAsArrayForQuery(), "id = {$page->getId()}")) {
                $_messages->add(ALERT_TYPE_CONFIRM, _('Page updated'));

                $pageModified = new \Page();
                $pageModified->setDataByObject($pageModified->getRecordById($pageId));

                $newImage = $pageModified->getImage();

                if ($oldImage and $newImage != $oldImage) {
                    $pageModified->deletePreviousImage($oldImage);
                }
            }

            $categoriesToAdd = $categoriesToRemove = [];

            foreach ($allCategoriesKeyValueArray as $key => $value) {
                if (isset($_POST['category-' . $key]) and $_POST['category-' . $key] == 't') {
                    if (!$page->hasCategory($key)) {
                        $categoriesToAdd[$key] = $key;
                    }
                }
                if ($page->hasCategory($key) and !isset($_POST['category-' . $key])) {
                    $categoriesToRemove[$key] = $key;
                }
            }

            $query = '';
            $queryError = false;

            if ($categoriesToAdd) {
                foreach ($categoriesToAdd as $categoryToAdd) {
                    $queryInsertCategories = "INSERT INTO link_pages_categories (id_page, id_category) VALUES ({$page->getId()}, {$categoryToAdd});";
                    if (!Database::query($queryInsertCategories)) {
                        $_messages->add(ALERT_TYPE_DEBUGQUERY, $queryInsertCategories);
                        $queryError = true;
                    }
                }
            }
            if ($categoriesToRemove) {
                $queryDeleteCategories = "DELETE FROM link_pages_categories WHERE id_page = {$page->getId()} AND id_category IN (" . implode(',', $categoriesToRemove) . ");";
                if (!Database::query($queryDeleteCategories)) {
                    $_messages->add(ALERT_TYPE_DEBUGQUERY, $queryDeleteCategories);
                    $queryError = true;
                }
            }

            if ($categoriesToAdd or $categoriesToRemove) {
                if ($queryError) {
                    $_messages->add(ALERT_TYPE_ERROR, _('Error updating categories for this page.'));
                } else {
                    $_messages->add(ALERT_TYPE_CONFIRM, _('Categories correctly assigned.'));
                }
            }
        } elseif ($action == 'create') {
            if ($newId = Database::queryInsert('pages', $form->getFieldsAsArrayForQuery(), 'id')) {
                $_messages->add(ALERT_TYPE_CONFIRM, _('Page inserted: <a href="/admin/pages/edit/' . $newId . '">Edit the new page</a>'));
            } else {
                $_messages->add(ALERT_TYPE_ERROR, _('Page not inserted'));
            }
        }
    }
} elseif (isset($_REQUEST['delete']) and $action == 'edit') {
    $page->delete();
}

$title = '';

if ($action == 'edit') {
    $title = _('Edit page');
} elseif ($action == 'create') {
    $title = _('Create page');
}

if ($page->returnDefaultTitle()) {
    $title .= ': ' . $page->returnDefaultTitle();
}
